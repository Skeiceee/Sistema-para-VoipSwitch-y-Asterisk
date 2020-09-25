<?php

namespace App\Http\Controllers;

use App\Portador;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
                $base_asterisk_one = DB::connection('asterisk')
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
                    $base_asterisk_one = $base_asterisk_one->whereRaw('dayofweek(c.calldate) between 2 AND 6')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'reduced' || $time == 2){
                    $base_asterisk_one = $base_asterisk_one->whereRaw('(dayofweek(c.calldate)= 7 OR dayofweek(c.calldate)= 1)')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'night' || $time == 3){
                    $base_asterisk_one = $base_asterisk_one->whereRaw('hour(c.calldate) between 0 AND 8')
                        ->get();  
                }

                $base_asterisk_two = DB::connection('asterisk2')
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
                    $base_asterisk_two = $base_asterisk_two->whereRaw('dayofweek(c.calldate) between 2 AND 6')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'reduced' || $time == 2){
                    $base_asterisk_two = $base_asterisk_two->whereRaw('(dayofweek(c.calldate)= 7 OR dayofweek(c.calldate)= 1)')
                        ->whereRaw('hour(c.calldate) between 9 AND 23')
                        ->get();
                }else if($time == 'night' || $time == 3){
                    $base_asterisk_two = $base_asterisk_two->whereRaw('hour(c.calldate) between 0 AND 8')
                        ->get();  
                }

                $base_asterisk_one[0]->segundos = $base_asterisk_one[0]->segundos + $base_asterisk_two[0]->segundos;
                $base_asterisk_one[0]->llamadas = $base_asterisk_one[0]->llamadas + $base_asterisk_two[0]->llamadas;
                $base_asterisk_one[0]->total = $base_asterisk_one[0]->total + $base_asterisk_two[0]->total;

                return $base_asterisk_one;
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
                if($pos == 2){
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
                $sheet->setCellValue('A'.$pos, 'Vozdigital');

                $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':E'.$pos);
                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                ));
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');
                
                $center = [ 
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
                ];
                $spreadsheet->getActiveSheet()->getStyle('A'.$pos)->getAlignment()->applyFromArray($center);

                $pos++;

                $pos_sum_one = $pos + 2;

                for ($i = 0; $i < count($start_dates); $i++){
                    if($i > 0){
                        $pos_sum_one = $pos + 3;
                    }

                    $ido = $portador->id_port;
                    $normal = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $normal_rates[$i], 1);
                    $reduced = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $reduced_rates[$i], 2);
                    $night = queryAccessCharge($start_dates[$i], $end_dates[$i], $ido, $night_rates[$i], 3);
                    
                    $title = 'Desde '.$start_dates[$i].' hasta '.$end_dates[$i];
                    $pos = newTable($spreadsheet, $sheet, $portador, $normal, $reduced, $night, $title, $pos);

                    $pos_sum_two = $pos;
                    $all_pos_sum[] = [$pos_sum_one, $pos_sum_two];
                }
                

                //Totales
                $string_sum_call = '';
                $string_sum_seconds = '';
                $string_sum_total = '';

                foreach($all_pos_sum as $key => $pos_sum){
                    if ($key === array_key_first($all_pos_sum)){
                        $string_sum_call .= '=SUM(C'.$pos_sum[0].':'.'C'.$pos_sum[1].')';
                        $string_sum_seconds .= '=SUM(D'.$pos_sum[0].':'.'D'.$pos_sum[1].')';
                        $string_sum_total .= '=SUM(E'.$pos_sum[0].':'.'E'.$pos_sum[1].')';
                    }
                    if($key <= array_key_last($all_pos_sum) && $key > array_key_first($all_pos_sum)){
                        $string_sum_call .= '+SUM(C'.$pos_sum[0].':'.'C'.$pos_sum[1].')';
                        $string_sum_seconds .= '+SUM(D'.$pos_sum[0].':'.'D'.$pos_sum[1].')';
                        $string_sum_total .= '+SUM(E'.$pos_sum[0].':'.'E'.$pos_sum[1].')';
                    }
                }

                $pos++;

                $sheet->setCellValue('A'.$pos, 'Total');
                $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':B'.$pos);

                $sheet->setCellValue('C'.$pos, $string_sum_call);
                $sheet->setCellValue('D'.$pos, $string_sum_seconds);
                $sheet->setCellValue('E'.$pos, $string_sum_total);
                
                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                ));

                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':E'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');
                
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('F1:G'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('F1:G'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');
                
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ];

                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A1:E'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('F1:G'.$pos)->applyFromArray($styleArray);
                $spreadsheet->getActiveSheet()->mergeCells('F1:G'.($pos - 1));
                
                $sheet->setCellValue('F'.$pos, 'C/IVA');
                $center = [ 
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 
                ];
                $spreadsheet->getActiveSheet()->getStyle('F'.$pos)->getAlignment()->applyFromArray($center);

                $sheet->setCellValue('G'.$pos, '=E'.$pos.'*1.19');
                //

                $spreadsheet->getActiveSheet()
                    ->getStyle('C1:D'.$pos)
                    ->getNumberFormat()
                    ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

                $spreadsheet->getActiveSheet()
                    ->getStyle('E1:E'.$pos)
                    ->getNumberFormat()
                    ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
                
                $spreadsheet->getActiveSheet()
                    ->getStyle('G1:G'.$pos)
                    ->getNumberFormat()
                    ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
                
                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
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
