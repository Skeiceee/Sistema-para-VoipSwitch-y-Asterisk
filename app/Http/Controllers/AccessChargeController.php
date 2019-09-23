<?php

namespace App\Http\Controllers;

use App\Portador;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use DB;

class AccessChargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portadores = Portador::select('id_port', 'portador')->get();
        return view('accesscharge.index', compact('portadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function download($namefile)
    {
        $file_name = $namefile.'.xlsx';
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'accesscharge'.DIRECTORY_SEPARATOR.$file_name;
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($fullpath, $file_name, $headers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        set_time_limit(0);
        if(request()->ajax()){
            /**
             * Get a collection of access charges according to date range and rate.
             *
             * @param int|string $time 1:normal, 2:reduced, 3:night
             * @return object
             */
            function queryAccessCharge(String $start_date, String $end_date, $ido, String $rate, $time = 'normal'){
                $base = DB::connection('asterisk')
                    ->table('cdr as c')
                    ->select(
                        DB::raw('sum(c.billsec) as segundos'),
                        DB::raw('count(c.userfield) as llamadas'),
                        DB::raw('sum(c.billsec) *  '.$rate.' as total')
                    )
                    ->whereBetween(
                        'c.calldate', 
                        [
                            DB::raw('str_to_date("'.$start_date.' 00:00:00", "%d/%m/%Y %H:%i:%s")'),
                            DB::raw('str_to_date("'.$end_date.'23:59:59", "%d/%m/%Y %H:%i:%s")')
                        ]
                    )
                    ->where('c.disposition', 'ANSWERED')
                    ->where('c.userfield', $ido)
                    ->where('c.userfield', '!=' ,'');
                
                if($time == 'normal' || $time == 1){
                    $base = $base->whereRaw('dayofweek(c.calldate) between 2 AND 6')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'reduced' || $time == 2){
                    $base = $base->whereRaw('(dayofweek(c.calldate)= 7 OR dayofweek(c.calldate)= 1)')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'night' || $time == 3){
                    $base = $base->whereRaw('hour(c.calldate) between 0 AND 8')
                        ->get();  
                }
                
                return $base;
            }

            function addAccessCharges(Object $sheet, String $namePortador, Object $accessCharges, String $time, $pos){
                foreach ($accessCharges as $accessCharge) {
                    $sheet->setCellValue('A'.$pos, $time);
                    $sheet->setCellValue('B'.$pos, $namePortador);
                    $sheet->setCellValue('C'.$pos, ($accessCharge->llamadas ? $accessCharge->llamadas : 0));
                    $sheet->setCellValue('D'.$pos, ($accessCharge->segundos ? $accessCharge->segundos : 0));
                    $sheet->setCellValue('E'.$pos, ($accessCharge->total ? $accessCharge->total : 0));
                }
            }

            function newTable(Spreadsheet $spreadsheet, Object $sheet, Object $portador, Object $normal, Object $reduced, Object $night, String $title, Int $pos = 1){
                if($pos == 1){
                    $center = [ 
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
                    ];
                    $spreadsheet->getActiveSheet()->getStyle('A:E')->getAlignment()->applyFromArray($center);
                }else{
                    $pos++;
                }
                $sheet->setCellValue('A'.$pos, $title);
                $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':E'.$pos);
                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                ));
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');      
                $pos++;

                $sheet->setCellValue('A'.$pos, 'Horario');
                $sheet->setCellValue('B'.$pos, 'Portador');
                $sheet->setCellValue('C'.$pos, 'Llamadas');
                $sheet->setCellValue('D'.$pos, 'Segundos');
                $sheet->setCellValue('E'.$pos, 'Total');
                
                $namePortador = $portador->portador;
                addAccessCharges($sheet, $namePortador, $normal, 'Normal', ++$pos);
                addAccessCharges($sheet, $namePortador, $reduced, 'Reducido', ++$pos);
                addAccessCharges($sheet, $namePortador, $night, 'Nocturno', ++$pos);

                foreach (range('A', 'E') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];

                foreach (range('A', 'E') as $column) {
                    $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle($column.'1:'.$column.$pos)->applyFromArray($styleArray);
                }

                return $pos;
            }

            $sigue = ($request->ido != null && $request->start_dates != null
                && $request->end_dates != null && $request->normal_rates != null
                && $request->reduced_rates != null && $request->night_rates != null);

            if($sigue){
                $portador = Portador::select('id_port', 'portador')->where('id_port', $request->ido)->first();
                $start_dates = $request->start_dates;
                $end_dates = $request->end_dates;
                $normal_rates = $request->normal_rates;
                $reduced_rates = $request->reduced_rates;
                $night_rates = $request->night_rates;
                $spreadsheet = new Spreadsheet();

                $sheet = $spreadsheet->getActiveSheet();
                $writer = new Xlsx($spreadsheet);
                
                $pos = 1;
                for ($i = 0; $i < count($start_dates); $i++){
                    $ido = $portador->id_port;
                    $normal = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $normal_rates[$i], 1);
                    $reduced = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $reduced_rates[$i], 2);
                    $night = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $night_rates[$i], 3);
                    
                    $title = 'Desde '.$start_dates[$i].' hasta '.$end_dates[$i];
                    $pos = newTable($spreadsheet, $sheet, $portador, $normal, $reduced, $night, $title, $pos);
                }
        
                ob_start();
                $writer->save('php://output');
                $content = ob_get_contents();
                ob_end_clean();
                
                $date = str_replace('/','', $start_dates[0]);
                $nameFile = $portador->id_port.'_'.$portador->portador.'_'.$date;
                $nameFile = str_replace(' ', '_', $nameFile);
                $nameFile = str_replace('.', '', $nameFile);
                
                Storage::disk('accesscharge')->put($nameFile.".xlsx", $content);

                return response()->json([
                    'filename' => $nameFile
                ], 200);
            }else{
                return response()->json([
                    'error' => 'Internal server error'
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
