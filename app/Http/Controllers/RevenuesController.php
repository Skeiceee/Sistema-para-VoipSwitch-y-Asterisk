<?php

namespace App\Http\Controllers;

use App\DailyRevenue;
use App\Http\Requests\DownloadAccomulatedRequest;
use App\RecurringCharge;
use App\Revenue;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RevenuesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Download excel
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $revenue = Revenue::find($id);
        $file_name = $revenue->file_name.'.xlsx';
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'revenues'.DIRECTORY_SEPARATOR.$file_name;
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($fullpath, $file_name, $headers);
    }

    public function downloadAccomulated(DownloadAccomulatedRequest $request)
    {
        $dates = explode(' al ',$request->date_accomulated);

        foreach ($dates as $key => $date) {
            $dates[$key] = str_replace('/', '-', $date);
        }

        $dailyRevenues = DailyRevenue::select(
            'login',
            DB::raw('sum( minutes_real ) AS minutes_real'),
            DB::raw('sum( seconds_real_total ) AS seconds_real_total'),
            DB::raw('sum( minutes_effective ) AS minutes_effective'),
            DB::raw('sum( seconds_effective_total ) AS seconds_effective_total'),
            DB::raw('sum( sale ) AS sale'),
            DB::raw('sum( cost ) AS cost'),
            DB::raw('voipswitchs.name AS name'),
            DB::raw('id_voipswitch')
        )
        ->leftJoin('voipswitchs', 'voipswitchs.id', 'daily_revenues.id_voipswitch')
        ->whereBetween('date',
            [
                DB::raw('str_to_date("'.$dates[0].' 00:00:00", "%d-%m-%Y %H:%i:%s")'),
                DB::raw('str_to_date("'.$dates[1].' 23:59:59", "%d-%m-%Y %H:%i:%s")')
            ]
        )
        ->groupBy('login', 'id_voipswitch', 'voipswitchs.name')
        ->orderBy('id_voipswitch', 'desc')
        ->orderBy('login', 'asc')
        ->get();
        
        $pos = 1;
        $totales = false;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);

        $old_name_voip = '';
        foreach ($dailyRevenues as $key => $dailyRevenue) {
            if($old_name_voip != $dailyRevenue->name){
                if($dailyRevenue->name != ''){
                    $old_name_voip = $dailyRevenue->name;
                }else{
                    $old_name_voip = $dailyRevenue->login;
                }

                $sheet->setCellValue('A'.$pos, $old_name_voip);
                $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':G'.$pos);
                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                ));

                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');        
                $pos++;
                $sheet->setCellValue('A'.$pos, 'Cliente');
                $sheet->setCellValue('B'.$pos, 'Minutos reales');
                $sheet->setCellValue('C'.$pos, 'Segundos reales totales');
                $sheet->setCellValue('D'.$pos, 'Minutos efectivos');
                $sheet->setCellValue('E'.$pos, 'Segundos efectivos totales');
                $sheet->setCellValue('F'.$pos, 'Venta');
                $sheet->setCellValue('G'.$pos, 'Costo');

                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                ));

                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b47eb6');        
                
                $posHeader = $pos++;
            }

            $sheet->setCellValue('A'.$pos, $dailyRevenue->login);
            $sheet->setCellValue('B'.$pos, $dailyRevenue->minutes_real);
            $sheet->setCellValue('C'.$pos, $dailyRevenue->seconds_real_total);
            $sheet->setCellValue('D'.$pos, $dailyRevenue->minutes_effective);
            $sheet->setCellValue('E'.$pos, $dailyRevenue->seconds_effective_total);
            $sheet->setCellValue('F'.$pos, $dailyRevenue->sale);
            $sheet->setCellValue('G'.$pos, $dailyRevenue->cost);

            $spreadsheet->getActiveSheet()
            ->getStyle('B'.$pos.':E'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');
            
            $spreadsheet->getActiveSheet()
            ->getStyle('F'.$pos.':G'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
            
            if(isset($dailyRevenues[$key+1])){
                if($old_name_voip !=  $dailyRevenues[$key+1]->name){
                    $totales = true;
                }else{
                    $totales = false;
                }
            }else{
                $totales = true;
            }

            if($totales){
                $pos++;
                $sheet->setCellValue('A'.$pos, 'Total');
                
                $styleArray = array(
                    'font'  => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'bold' => true
                        )
                    );
                
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->applyFromArray($styleArray);
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f79646');  
                
                $spreadsheet->getActiveSheet()
                ->getStyle('B'.$pos.':E'.$pos)
                ->getNumberFormat()
                ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');
                
                $spreadsheet->getActiveSheet()
                ->getStyle('F'.$pos.':G'.$pos)
                ->getNumberFormat()
                ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
                
                foreach (range('B', 'G') as $column) {
                    $sheet->setCellValue($column.$pos, '=SUM('.$column.($posHeader+1).':'.$column.($pos-1).')');
                }
            }
            
            
            $pos++;
        }
        
        // Cargos recurrentes
        $recurringCharges = RecurringCharge::select(
            'recurring_charges.id',
            'clients.name',
            'recurring_charges.date',
            'recurring_charges.date_service_start',
            'recurring_charges.description',
            'recurring_charges.isPerMonth',
            'recurring_charges.cost_unit',
            'recurring_charges.quantity',
            'recurring_charges.cost_total',
            'recurring_charges.money_type'
        )
        ->join('clients', 'clients.id', 'recurring_charges.id_client')
        ->whereBetween('date',
        [
            DB::raw('str_to_date("'.$dates[0].' 00:00:00", "%d-%m-%Y %H:%i:%s")'),
            DB::raw('str_to_date("'.$dates[1].' 23:59:59", "%d-%m-%Y %H:%i:%s")')
            ]
            )
        ->orWhere('date', '=', null)->get();

        $pos_rc = 1;
        $sheet->setCellValue('I'.$pos_rc, 'Cargos recurrentes');
        $spreadsheet->getActiveSheet()->mergeCells('I'.$pos_rc.':P'.$pos_rc);
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
            )
        );

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('I'.$pos_rc.':P'.$pos_rc)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('I'.$pos_rc.':P'.$pos_rc)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');    

        $pos_rc++;

        $sheet->setCellValue('I'.$pos_rc, 'Fecha');
        $sheet->setCellValue('J'.$pos_rc, 'Cliente');
        $sheet->setCellValue('K'.$pos_rc, 'Descripción');
        $sheet->setCellValue('L'.$pos_rc, 'Inicio de servicio');
        $sheet->setCellValue('M'.$pos_rc, 'Costo unitario');
        $sheet->setCellValue('N'.$pos_rc, 'Cantidad');
        $sheet->setCellValue('O'.$pos_rc, 'Costo total');
        $sheet->setCellValue('P'.$pos_rc, 'Tipo de moneda');
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('I'.$pos_rc.':P'.$pos_rc)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('I'.$pos_rc.':P'.$pos_rc)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b47eb6');        
        

        $pos_rc++;

        foreach ($recurringCharges as $recurringCharge) {
            $sheet->setCellValue('I'.$pos_rc, $recurringCharge->date != null ? $recurringCharge->date : 'Mensual');
            $sheet->setCellValue('J'.$pos_rc, $recurringCharge->name);
            $sheet->setCellValue('K'.$pos_rc, $recurringCharge->description);
            $sheet->setCellValue('L'.$pos_rc, $recurringCharge->date_service_start);
            $sheet->setCellValue('M'.$pos_rc, $recurringCharge->cost_unit);
            $sheet->setCellValue('N'.$pos_rc, $recurringCharge->quantity);
            $sheet->setCellValue('O'.$pos_rc, '=M'.$pos_rc.'*N'.$pos_rc);
            $sheet->setCellValue('P'.$pos_rc, $recurringCharge->money_type);
            
            $spreadsheet->getActiveSheet()
            ->getStyle('M'.$pos_rc.':N'.$pos_rc)
            ->getNumberFormat()
            ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

            $spreadsheet->getActiveSheet()
            ->getStyle('O'.$pos_rc)
            ->getNumberFormat()
            ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
            
            $pos_rc++;
        }
        // ------------------

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        // Cargos recurrentes   
        foreach (range('I', 'P') as $column) {
            $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle($column.'1:'.$column.($pos_rc-1))->applyFromArray($styleArray);
        }
        foreach (range('I', 'P') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        // ------------------

        foreach (range('A', 'G') as $column) {
            $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle($column.'1:'.$column.($pos-1))->applyFromArray($styleArray);
        }

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $center = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];

        $spreadsheet->getActiveSheet()
        ->getStyle('A1'.':G'.($pos-1))
        ->getAlignment()
        ->applyFromArray($center);
        // Cargos recurrentes  
        $spreadsheet->getActiveSheet()
        ->getStyle('I1'.':P'.($pos_rc-1))
        ->getAlignment()
        ->applyFromArray($center);
        // ------------------
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        $file_name = 'Consumos_acomulados_del_';
        $file_name .= str_replace('/', '_', $request->date_accomulated);
        $file_name = str_replace(' ', '_', $file_name);

        return response()->excel($content, $file_name);
    }

    public function downloadPerClient(Request $request)
    {
        list($id_client, $name_client, $id_voipswitch) = explode(';', $request->id_client);

        $date = str_replace(' ', '', $request->date_month);
        list($month, $year) = explode('/', substr($date, (strpos($date, '-') + 1)));

        $start_date = Carbon::create($year, $month, 1, 0, 0, 0, 'America/Santiago');
        $end_date = (Carbon::create($year, $month, 1, 0, 0, 0, 'America/Santiago'))->endOfMonth();
        
        $revenues = DB::connection('mysql')
            ->table('daily_revenues as dr')
            ->select(
                'date',
                'login',
                DB::raw('dr.minutes_real AS minutes_real'),
                DB::raw('dr.seconds_real_total AS seconds_real_total'),
                DB::raw('dr.minutes_effective AS minutes_effective'),
                DB::raw('dr.seconds_effective_total AS seconds_effective_total'),
                DB::raw('dr.sale AS sale'),
                DB::raw('dr.cost AS cost')
            )
            ->leftJoin('voipswitchs as vs', 'vs.id', 'dr.id_voipswitch')
            ->where('id_client', $id_client)
            ->where('login', 'like', '%'.$name_client.'%')
            ->where('id_voipswitch', $id_voipswitch)
            ->whereBetween(
                'date',
                [
                    $start_date->toDateString(),
                    $end_date->toDateString()
                ]
            )
            ->get();
        
        $vs_name = DB::connection('mysql')->table('voipswitchs')->where('id', $id_voipswitch)->value('name');
        $vs_name = $vs_name != null ? $vs_name : 'Interconexion directa';
        
        $pos = 1;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);

        $sheet->setCellValue('A'.$pos, $vs_name);
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':H'.$pos);
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4f81bd');        
        
        $pos++;

        $sheet->setCellValue('A'.$pos, 'Fecha');
        $sheet->setCellValue('B'.$pos, 'Cliente');
        $sheet->setCellValue('C'.$pos, 'Minutos reales');
        $sheet->setCellValue('D'.$pos, 'Segundos reales totales');
        $sheet->setCellValue('E'.$pos, 'Minutos efectivos');
        $sheet->setCellValue('F'.$pos, 'Segundos efectivos totales');
        $sheet->setCellValue('G'.$pos, 'Venta');
        $sheet->setCellValue('H'.$pos, 'Costo');
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b47eb6');        
        
        $posHeader = $pos++;

        foreach ($revenues as $revenue) {
            $sheet->setCellValue('A'.$pos, $revenue->date);
            $sheet->setCellValue('B'.$pos, $revenue->login);
            $sheet->setCellValue('C'.$pos, $revenue->minutes_real);
            $sheet->setCellValue('D'.$pos, $revenue->seconds_real_total);
            $sheet->setCellValue('E'.$pos, $revenue->minutes_effective);
            $sheet->setCellValue('F'.$pos, $revenue->seconds_effective_total);
            $sheet->setCellValue('G'.$pos, $revenue->sale);
            $sheet->setCellValue('H'.$pos, $revenue->cost);

            $spreadsheet->getActiveSheet()
            ->getStyle('C'.$pos.':F'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');
            
            $spreadsheet->getActiveSheet()
            ->getStyle('G'.$pos.':H'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
            
            $pos++;
        }

        $sheet->setCellValue('A'.$pos, 'Total');
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':B'.$pos);

        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
                )
            );
        
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':H'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f79646');  
        
        $spreadsheet->getActiveSheet()
        ->getStyle('C'.$pos.':F'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');
        
        $spreadsheet->getActiveSheet()
        ->getStyle('G'.$pos.':H'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');
        
        foreach (range('C', 'H') as $column) {
            $sheet->setCellValue($column.$pos, '=SUM('.$column.($posHeader+1).':'.$column.($pos-1).')');
        }

        $pos++;

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        foreach (range('A', 'H') as $column) {
            $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle($column.'1:'.$column.($pos-1))->applyFromArray($styleArray);
        }

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        $center= [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];

        $spreadsheet->getActiveSheet()
        ->getStyle('A1'.':H'.($pos-1))
        ->getAlignment()
        ->applyFromArray($center);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        
        $file_name = 'Consumos_mensuales_del_';
        $file_name .= $month.'_'.$year.'_de_'.$name_client.'_en_'.$vs_name;

        return response()->excel($content, $file_name);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $sigue = false;
            $month = intval(request()->get('month')); // Si es null el valor es igual a 0
            $year = intval(request()->get('year')); // Si es null el valor es igual a 0

            if($month != 0 and $year != 0){
                foreach (range(1, 12) as $number) {
                    if($month == $number){
                        $sigue = true;
                    }
                }
            }
            
            if($sigue){
                $date_start = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->startOfMonth();
                $date_end = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->endOfMonth();
            }else{
                $date_start = (new Carbon('first day of this month'))->startOfMonth();
                $date_end = (new Carbon('first day of this month'))->endOfMonth();
            }

            return datatables()->of(
                Revenue::select('id', 'date', 'description')
                    ->whereBetween('date',
                        [
                            DB::raw('str_to_date("'.$date_start->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")'),
                            DB::raw('str_to_date("'.$date_end->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")')
                        ]
                    )
            )
            ->addColumn('action', 'actions.revenues')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            
        }

        $clients = DB::connection('mysql')
            ->table('daily_revenues as dr')
            ->select(
                'dr.id_client', 
                'dr.login', 
                'dr.id_voipswitch',
                'vs.name'
            )
            ->leftJoin('voipswitchs as vs', 'vs.id', 'dr.id_voipswitch')
            ->distinct()
            ->get();

        $startYesterday = Carbon::yesterday()->toDateTimeString();
        $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        $dailyRevenues = DailyRevenue::select(
            'daily_revenues.login', 
            'voipswitchs.name as name', 
            'daily_revenues.minutes_real', 
            'daily_revenues.minutes_effective',
            'daily_revenues.sale', 
            'daily_revenues.cost', 
            DB::raw('(sale-cost) as margin')            
        )
        ->leftJoin('voipswitchs', 'voipswitchs.id', 'daily_revenues.id_voipswitch')
        ->whereBetween(
            'date', 
            [
                $startYesterday, 
                $endYesterday
            ]
        )
        ->orderBy('margin', 'desc')
        ->get();
            
        $yesterday = Carbon::yesterday()->format('d-m-Y');
        return view('revenues.index', compact('clients', 'dailyRevenues', 'yesterday'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
