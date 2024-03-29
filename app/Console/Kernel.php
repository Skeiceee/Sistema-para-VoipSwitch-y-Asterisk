<?php

namespace App\Console;

use App\Revenue;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @return integer
     */
    protected static function newTable(Spreadsheet $spreadsheet, Object $sheet, Object $revenues, String $title, Int $pos = 1){
        if($pos == 1){
            $center= [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ];
            $spreadsheet->getActiveSheet()->getStyle('A:G')->getAlignment()->applyFromArray($center);
        }else{
            $pos++;
        }

        $sheet->setCellValue('A'.$pos, $title);
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

        $pos++;
        foreach ($revenues as $offset => $revenue) {
            $sheet->setCellValue('A'.$pos, $revenue->customer);
            $sheet->setCellValue('B'.$pos, $revenue->minutes_real);
            $sheet->setCellValue('C'.$pos, $revenue->seconds_real_total);
            $sheet->setCellValue('D'.$pos, $revenue->minutes_effective);
            $sheet->setCellValue('E'.$pos, $revenue->seconds_effective_total);
            $sheet->setCellValue('F'.$pos, $revenue->sale);
            $sheet->setCellValue('G'.$pos, $revenue->cost);
            if($pos % 2 != 0){
                $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('f2f2f2');
            }
            $pos++;
        }

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $posHeader = ($pos - $revenues->count()) - 1;
        $posFirstRevenue = ($pos - $revenues->count());

        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$posHeader.':G'.$posHeader)->applyFromArray($styleArray);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$posHeader.':G'.$posHeader)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b47eb6');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        foreach (range('A', 'G') as $column) {
            $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle($column.'1:'.$column.$pos)->applyFromArray($styleArray);
        }

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':G'.$pos)->applyFromArray($styleArray);
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
            ->getStyle('B'.$posFirstRevenue.':E'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
            ->getStyle('F'.$posFirstRevenue.':G'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];

        $spreadsheet->getActiveSheet()
            ->getStyle('B'.$posFirstRevenue.':G'.$pos)
            ->getAlignment()
            ->applyFromArray($styleArray);

        foreach (range('B', 'G') as $column) {
            $sheet->setCellValue($column.$pos, '=SUM('.$column.$posFirstRevenue.':'.$column.($pos-1).')');
        }

        return $pos;
    }

    protected static function revenuesCondellQuery(String $nameDataBase, String $start, String $end){
        return DB::connection('condell.'.$nameDataBase)
                ->table('calls as c')
                ->select(
                    'c.id_client as id_customer',
                    'cs.login as customer',
                    DB::raw('round((sum(c.duration)/60)) as minutes_real'),
                    DB::raw('round(sum(c.duration)) as seconds_real_total'),
                    DB::raw('round((sum(c.duration)/60)) as minutes_effective'),
                    DB::raw('round(sum(c.duration)) as seconds_effective_total'),
                    DB::raw('round(sum(c.cost), 2) as sale'),
                    DB::raw('round(sum(c.costD), 4) as cost')
                )
                ->join('clientsip as cs', 'c.id_client', 'cs.id_client')
                ->whereBetween(
                    'c.call_start',
                    [
                        DB::raw('str_to_date("'.$start.'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$end.'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
                ->groupBy('c.id_client', 'cs.login')
                ->orderBy('sale', 'desc')
                ->get();
    }
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Crea el Excel diario de consumos, se ejecuta a las 4 de la mañana.
        $schedule->call(function(){
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $writer = new Xlsx($spreadsheet);

            $startYesterday = Carbon::yesterday()->toDateTimeString();
            $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

            $revenuesArgentina = DB::connection('argentina')
                ->table('calls as c')
                ->select(
                    'c.id_client as id_customer',
                    'i.Login as customer',
                    DB::raw('round((sum(c.duration)/60)) as minutes_real'),
                    DB::raw('round(sum(c.duration)) as seconds_real_total'),
                    DB::raw('round((sum(c.effective_duration)/60)) as minutes_effective'),
                    DB::raw('round(sum(c.effective_duration)) as seconds_effective_total'),
                    DB::raw('round(sum(c.cost), 2) as sale'),
                    DB::raw('round(sum(c.costD), 4) as cost')
                )
                ->join('invoiceclients as i', 'c.id_client', 'i.IdClient')
                ->whereBetween(
                    'c.call_start',
                    [
                        DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
                ->where('c.client_type', '=' , DB::raw('i.Type'))
                ->whereRaw('(id_client != 1)')
                ->groupBy('c.id_client', 'i.Login')
                ->orderBy('sale', 'desc')
                ->get();

            // $revenuesWholesale = DB::connection('wholesale')
            //     ->table('calls as c')
            //     ->select(
            //         'c.id_client as id_customer',
            //         'i.Login as customer',
            //         DB::raw('round((sum(c.duration)/60)) as minutes_real'),
            //         DB::raw('round(sum(c.duration)) as seconds_real_total'),
            //         DB::raw('round((sum(c.effective_duration)/60)) as minutes_effective'),
            //         DB::raw('round(sum(c.effective_duration)) as seconds_effective_total'),
            //         DB::raw('round(sum(c.cost), 2) as sale'),
            //         DB::raw('round(sum(c.costD), 4) as cost')
            //     )
            //     ->join('invoiceclients as i', 'c.id_client', 'i.IdClient')
            //     ->whereBetween(
            //         'c.call_start',
            //         [
            //             DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
            //             DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
            //         ]
            //     )
            //     ->where('c.client_type', '=' , DB::raw('i.Type'))
            //     ->groupBy('c.id_client', 'i.Login')
            //     ->orderBy('sale', 'desc')
            //     ->get();

            $revenuesSistek = DB::connection('asterisk')
                ->table('cdr as c')
                ->select(
                    DB::raw('"Sistek" AS customer'),
                    DB::raw('sum( c.billsec )/ 60 AS minutes_real'),
                    DB::raw('sum( c.billsec ) AS seconds_real_total'),
                    DB::raw('sum( c.billsec )/ 60 AS minutes_effective'),
                    DB::raw('sum( c.billsec ) AS seconds_effective_total'),
                    DB::raw('sum( c.billsec ) * 0.11666666666 AS sale'),
                    DB::raw('"0" AS cost')
                )
                ->where('c.channel', 'like', '%SIP/Sistek%')
                ->where('c.disposition', '=', 'ANSWERED')
                ->whereBetween(
                    'c.calldate',
                    [
                        DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
                ->get();
            
            $revenuesMagnusBilling = DB::connection('magnusbilling')
                    ->table('pkg_cdr_summary_day_user as cdr')
                    ->select(
                        DB::raw('usr.username AS customer'),
                        DB::raw('ROUND( sessiontime / 60 ) AS minutes_real'),
                        DB::raw('sessiontime AS seconds_real_total'),
                        DB::raw('ROUND( sessiontime / 60 ) AS minutes_effective'),
                        DB::raw('sessiontime AS seconds_effective_total'),
                        DB::raw('sessionbill AS sale'),
                        DB::raw('buycost AS cost'),
                    )
                    ->join('pkg_user as usr', 'cdr.id_user', '=', 'usr.id')
                    ->whereBetween(
                        'cdr.day',
                        [
                            DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                            DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                        ]
                    )
                    ->groupBy('usr.username')
                    ->orderBy('sale', 'desc')
                    ->get();

            $revenuesHeavyuser = $this::revenuesCondellQuery('heavyuser', $startYesterday, $endYesterday);
            $revenuesSynergo = $this::revenuesCondellQuery('synergo', $startYesterday, $endYesterday);
            $revenuesRetail = $this::revenuesCondellQuery('retail', $startYesterday, $endYesterday);

            $pos = 1;
            if($revenuesArgentina->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesArgentina, 'Argentina');
            }
            if($revenuesHeavyuser->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesHeavyuser, 'Heavyuser', $pos);
            }
            if($revenuesSynergo->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesSynergo, 'Synergo', $pos);
            }
            if($revenuesRetail->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesRetail, 'Retail', $pos);
            }
            if($revenuesSistek->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesSistek, 'Sistek', $pos);
            }
            if($revenuesMagnusBilling->count() > 0){
                $pos = $this::newTable($spreadsheet, $sheet, $revenuesMagnusBilling, 'MagnusBilling', $pos);
            }

            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            $nameFile = Carbon::yesterday()->format('Y-m-d');
            Storage::disk('revenues')->put($nameFile.".xlsx", $content);

            $days = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
            $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

            $yesterday = Carbon::yesterday();
            $revenue = new Revenue();
            $revenue->date = $yesterday;
            $revenue->description = 'Consumos del '.$days[$yesterday->format('w')].', '.$yesterday->format('d').' de '.$months[((int)$yesterday->format('n') - 1)].' del '.$yesterday->format('Y');
            $revenue->file_name = $nameFile;
            $revenue->save();

        })->dailyAt('05:00')->timezone('America/Santiago');

        //Agrega a la tabla 'avarage_calls' los datos del dia anterior.
        // $schedule->call(function () {
        //     $startYesterday = Carbon::yesterday()->toDateTimeString();
        //     $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        //     $avgCalls = DB::connection('asterisk.nostrict')
        //         ->table('report')
        //         ->distinct()
        //         ->select(
        //             'date',
        //             DB::raw('round(avg(active_calls)) as avg'),
        //             DB::raw('min(active_calls) as min'),
        //             DB::raw('max(active_calls) as max')
        //         )
        //         ->whereBetween(
        //             'date',
        //             [
        //                 DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
        //                 DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
        //             ]
        //         )
        //         ->groupBy(DB::raw('hour(date)'))
        //         ->get();

        //     $toInsert = [];
        //     foreach($avgCalls as $record) {
        //         $toInsert[] = (array)$record;
        //     }

        //     DB::connection('mysql')->table('average_calls')->insert($toInsert);

        // })->dailyAt('05:00')->timezone('America/Santiago');

        //Agrega a la tabla 'avarage_calls' los datos del dia anterior (Las dos interconexiones).
        $schedule->command('daily:averagecalls')->dailyAt('05:00')->timezone('America/Santiago');

        // Agrega a la tabla 'daily_revenue' los consumos diarios.
        $schedule->command('daily:revenues')->dailyAt('08:00')->timezone('America/Santiago');

        // Crea los archivos diarios de CDRs.
        $schedule->command('daily:cdrs')->dailyAt('05:00')->timezone('America/Santiago');
        
        //Crea el reporte diario por hora de promedio y el peak de las sesiones de Movistar
        $schedule->command('daily:sessionsmovistar')->dailyAt('08:00')->timezone('America/Santiago');
        
        //Envia el correo de saldos a los clientes.
        $schedule->command('daily:processedcalls')->dailyAt('08:00')->timezone('America/Santiago');
        
        // Crea los archivos mensuales de los cargos de acceso entrantes.
        $schedule->command('monthly:inboundaccesscharge')->monthlyOn(1, '08:00')->timezone('America/Santiago');
        
        // Envia el correo de consumos diarios.
        $schedule->command('email:accesscharge')->dailyAt('08:00')->timezone('America/Santiago');

        // Envia el correo que avisa de las sessiones.
        $schedule->command('email:alarmsessions')->cron('0 */1 * * *')->timezone('America/Santiago');
    
        //Envia el correo de consumos a los clientes.
        $schedule->command('email:clientsrevenues')->dailyAt('08:00')->timezone('America/Santiago');

        //Envia el correo de saldos a los clientes.
        $schedule->command('email:accountstate')->dailyAt('08:00')->timezone('America/Santiago');

        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
