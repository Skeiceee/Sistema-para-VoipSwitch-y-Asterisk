<?php

namespace App\Console\Commands;

use App\InboundAccessCharge;
use App\Portador;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonthlyInboundAccessCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:inboundaccesscharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create monthly archive of Inbound Access Charge';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startFirstPeriod = (new Carbon('first day of last month'))->hour(0)->minute(0)->second(0);
        $endFirstPeriod =  (new Carbon('last day of last month'))->day(24)->hour(23)->minute(59)->second(59);

        $startLastPeriod = (new Carbon('first day of last month'))->day(25)->hour(0)->minute(0)->second(0);
        $endLastPeriod =  (new Carbon('last day of last month'))->hour(23)->minute(59)->second(59);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);

        // Primer periodo.
        $pos = 1;
        $posHeader = $pos;
        $sheet->setCellValue('A'.$pos, 'Desde '.$startFirstPeriod->format('d/m/Y').' hasta el '.$endFirstPeriod->format('d/m/Y'));
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':K'.$pos);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');
        
        $pos++;
        $sheet->setCellValue('A'.$pos, 'IDO');
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':A'.($pos + 1));
        $sheet->setCellValue('B'.$pos, 'Empresa');
        $spreadsheet->getActiveSheet()->mergeCells('B'.$pos.':B'.($pos + 1));
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':B'.($pos + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');
        
        $sheet->setCellValue('C'.$pos, 'Normal');
        $spreadsheet->getActiveSheet()->mergeCells('C'.$pos.':E'.$pos);
        $sheet->setCellValue('F'.$pos, 'Reducido');
        $spreadsheet->getActiveSheet()->mergeCells('F'.$pos.':H'.$pos);
        $sheet->setCellValue('I'.$pos, 'Nocturno');
        $spreadsheet->getActiveSheet()->mergeCells('I'.$pos.':K'.$pos);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');

        $pos++;

        $sub_columns = [
            'Segundos',
            'Llamadas',
            'Monto'
        ];
        
        $i = 0;
        foreach (range('C', 'K') as $column) {
            if ($i > 2) { $i = 0; };
            $sheet->setCellValue($column.$pos, $sub_columns[$i]);
            $i++;
        }

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('C'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5b9bd5');

        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$posHeader.':K'.$pos)->applyFromArray($styleArray);

        $posFirstRow = $pos;
        $idos = DB::connection('asterisk.nostrict')->table('cdr')
            ->select('in_userfield')
            ->distinct()
            ->whereBetween('calldate', [
                    $startFirstPeriod->format('Y-m-d H:i:s'),
                    $endLastPeriod->format('Y-m-d H:i:s')
                ]
            )
            ->where('disposition', 'ANSWERED')
            ->where(function ($query) {
                $query->whereBetween(DB::raw('CAST(cdr.in_userfield AS INTEGER)'), [200,499])
                    ->orWhereBetween(DB::raw('CAST(cdr.in_userfield AS INTEGER)'), [700,799]);
            })
            ->orderBy('in_userfield', 'asc')
            ->get();
        
        foreach ($idos as $ido) {
            $pos++;
            $sheet->setCellValue('A'.$pos, $ido->in_userfield);

            $portador = Portador::where('id_port', $ido->in_userfield)->first();
            if($portador){
                $sheet->setCellValue('B'.$pos, $portador->portador);
            }
    
            $normal = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startFirstPeriod->format('Y-m-d H:i:s'),
                    $endFirstPeriod->format('Y-m-d H:i:s')
                ])
                ->whereRaw('dayofweek(calldate) between 2 and 6 ')
                ->whereRaw('hour(calldate) between 9 and 23 ')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('C'.$pos, $normal->seconds != null ? $normal->seconds : 0);
                $sheet->setCellValue('D'.$pos, $normal->calls != null ? $normal->calls : 0);
            }else{
                $sheet->setCellValue('C'.$pos, 0);
                $sheet->setCellValue('D'.$pos, 0);
            }

            $reduced = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startFirstPeriod->format('Y-m-d H:i:s'),
                    $endFirstPeriod->format('Y-m-d H:i:s')
                ])
                ->where(function ($query) {
                    $query->where(DB::raw('dayofweek(calldate)'), 7)
                        ->orWhere(DB::raw('dayofweek(calldate)'), 1);
                })
                ->whereRaw('hour(calldate) between 9 and 23')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('F'.$pos, $reduced->seconds != null ? $reduced->seconds : 0);
                $sheet->setCellValue('G'.$pos, $reduced->calls != null ? $reduced->calls : 0);
            }else{
                $sheet->setCellValue('F'.$pos, 0);
                $sheet->setCellValue('G'.$pos, 0);
            }

            $night = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startFirstPeriod->format('Y-m-d H:i:s'),
                    $endFirstPeriod->format('Y-m-d H:i:s')
                ])
                ->whereRaw('hour(cdr.calldate) between 0 and 8')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('I'.$pos, $night->seconds != null ? $night->seconds : 0);
                $sheet->setCellValue('J'.$pos, $night->calls != null ? $night->calls : 0);
            }else{
                $sheet->setCellValue('I'.$pos, 0);
                $sheet->setCellValue('J'.$pos, 0);
            }

            $sheet->setCellValue('O'.$pos, 0);
            $sheet->setCellValue('P'.$pos, 0);
            $sheet->setCellValue('Q'.$pos, 0);

            $sheet->setCellValue('L'.$pos, '=O'.$pos.'/'.(1.19));
            $sheet->setCellValue('M'.$pos, '=P'.$pos.'/'.(1.19));
            $sheet->setCellValue('N'.$pos, '=Q'.$pos.'/'.(1.19));

            $sheet->setCellValue('E'.$pos, '=C'.$pos.'*L'.$pos);
            $sheet->setCellValue('H'.$pos, '=F'.$pos.'*M'.$pos);
            $sheet->setCellValue('K'.$pos, '=I'.$pos.'*N'.$pos);
        }

        // Formato en miles
        $spreadsheet->getActiveSheet()
        ->getStyle('C'.$posFirstRow.':D'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('F'.$posFirstRow.':G'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('I'.$posFirstRow.':J'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        // Formato de dinero
        $spreadsheet->getActiveSheet()
        ->getStyle('E'.$posFirstRow.':E'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('H'.$posFirstRow.':H'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('K'.$posFirstRow.':K'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        // Ajusta las celdas al tamaño de contenido.
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Agrega los bordes a las celdas.
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A1:K'.$pos)->applyFromArray($styleArray);

        // Centra todas las celdas.
        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];
        $spreadsheet->getActiveSheet()->getStyle('A'.$posHeader.':K'.$pos)->getAlignment()->applyFromArray($styleArray);

        // Centra horizontalmente a la izquierda.
        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
        ];

        $spreadsheet->getActiveSheet()->getStyle('B'.$posFirstRow.':B'.$pos)->getAlignment()->applyFromArray($styleArray);

        // Segundo periodo.
        $pos++;
        $posHeader = $pos;
        $sheet->setCellValue('A'.$pos, 'Desde '.$startLastPeriod->format('d/m/Y').' hasta el '.$endLastPeriod->format('d/m/Y'));
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':K'.$pos);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');
        
        $pos++;
        $sheet->setCellValue('A'.$pos, 'IDO');
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':A'.($pos + 1));
        $sheet->setCellValue('B'.$pos, 'Empresa');
        $spreadsheet->getActiveSheet()->mergeCells('B'.$pos.':B'.($pos + 1));
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':B'.($pos + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');
        
        $sheet->setCellValue('C'.$pos, 'Normal');
        $spreadsheet->getActiveSheet()->mergeCells('C'.$pos.':E'.$pos);
        $sheet->setCellValue('F'.$pos, 'Reducido');
        $spreadsheet->getActiveSheet()->mergeCells('F'.$pos.':H'.$pos);
        $sheet->setCellValue('I'.$pos, 'Nocturno');
        $spreadsheet->getActiveSheet()->mergeCells('I'.$pos.':K'.$pos);
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');

        $pos++;

        $sub_columns = [
            'Segundos',
            'Llamadas',
            'Monto'
        ];
        
        $i = 0;
        foreach (range('C', 'K') as $column) {
            if ($i > 2) { $i = 0; };
            $sheet->setCellValue($column.$pos, $sub_columns[$i]);
            $i++;
        }

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('C'.$pos.':K'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5b9bd5');

        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$posHeader.':K'.$pos)->applyFromArray($styleArray);

        $posFirstRow = $pos;
        $idos = DB::connection('asterisk.nostrict')->table('cdr')
            ->select('in_userfield')
            ->distinct()
            ->whereBetween('calldate', [
                    $startFirstPeriod->format('Y-m-d H:i:s'),
                    $endLastPeriod->format('Y-m-d H:i:s')
                ]
            )
            ->where('disposition', 'ANSWERED')
            ->where(function ($query) {
                $query->whereBetween(DB::raw('CAST(cdr.in_userfield AS INTEGER)'), [200,499])
                    ->orWhereBetween(DB::raw('CAST(cdr.in_userfield AS INTEGER)'), [700,799]);
            })
            ->orderBy('in_userfield', 'asc')
            ->get();
        foreach ($idos as $ido) {
            $pos++;
            $sheet->setCellValue('A'.$pos, $ido->in_userfield);

            $portador = Portador::where('id_port', $ido->in_userfield)->first();
            if($portador){
                $sheet->setCellValue('B'.$pos, $portador->portador);
            }
    
            $normal = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startLastPeriod->format('Y-m-d H:i:s'),
                    $endLastPeriod->format('Y-m-d H:i:s')
                ])
                ->whereRaw('dayofweek(calldate) between 2 and 6 ')
                ->whereRaw('hour(calldate) between 9 and 23 ')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('C'.$pos, $normal->seconds != null ? $normal->seconds : 0);
                $sheet->setCellValue('D'.$pos, $normal->calls != null ? $normal->calls : 0);
            }else{
                $sheet->setCellValue('C'.$pos, 0);
                $sheet->setCellValue('D'.$pos, 0);
            }

            $reduced = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startLastPeriod->format('Y-m-d H:i:s'),
                    $endLastPeriod->format('Y-m-d H:i:s')
                ])
                ->where(function ($query) {
                    $query->where(DB::raw('dayofweek(calldate)'), 7)
                        ->orWhere(DB::raw('dayofweek(calldate)'), 1);
                })
                ->whereRaw('hour(calldate) between 9 and 23')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('F'.$pos, $reduced->seconds != null ? $reduced->seconds : 0);
                $sheet->setCellValue('G'.$pos, $reduced->calls != null ? $reduced->calls : 0);
            }else{
                $sheet->setCellValue('F'.$pos, 0);
                $sheet->setCellValue('G'.$pos, 0);
            }

            $night = DB::connection('asterisk.nostrict')->table('cdr')
                ->select(
                    'in_userfield',
                    DB::raw('sum( billsec ) AS "seconds"'),
                    DB::raw('count(*) AS "calls"')
                )
                ->whereBetween('calldate', [
                    $startLastPeriod->format('Y-m-d H:i:s'),
                    $endLastPeriod->format('Y-m-d H:i:s')
                ])
                ->whereRaw('hour(cdr.calldate) between 0 and 8')
                ->where('disposition', 'ANSWERED')
                ->where('in_userfield', $ido->in_userfield)
                ->first();

            if($normal){
                $sheet->setCellValue('I'.$pos, $night->seconds != null ? $night->seconds : 0);
                $sheet->setCellValue('J'.$pos, $night->calls != null ? $night->calls : 0);
            }else{
                $sheet->setCellValue('I'.$pos, 0);
                $sheet->setCellValue('J'.$pos, 0);
            }

            $sheet->setCellValue('O'.$pos, 0);
            $sheet->setCellValue('P'.$pos, 0);
            $sheet->setCellValue('Q'.$pos, 0);

            $sheet->setCellValue('L'.$pos, '=O'.$pos.'/'.(1.19));
            $sheet->setCellValue('M'.$pos, '=P'.$pos.'/'.(1.19));
            $sheet->setCellValue('N'.$pos, '=Q'.$pos.'/'.(1.19));

            $sheet->setCellValue('E'.$pos, '=C'.$pos.'*L'.$pos);
            $sheet->setCellValue('H'.$pos, '=F'.$pos.'*M'.$pos);
            $sheet->setCellValue('K'.$pos, '=I'.$pos.'*N'.$pos);
        }

        // Formato en miles
        $spreadsheet->getActiveSheet()
        ->getStyle('C'.$posFirstRow.':D'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('F'.$posFirstRow.':G'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('I'.$posFirstRow.':J'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        // Formato de dinero
        $spreadsheet->getActiveSheet()
        ->getStyle('E'.$posFirstRow.':E'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('H'.$posFirstRow.':H'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        $spreadsheet->getActiveSheet()
        ->getStyle('K'.$posFirstRow.':K'.$pos)
        ->getNumberFormat()
        ->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"_);_(@_)');

        // Ajusta las celdas al tamaño de contenido.
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Agrega los bordes a las celdas.
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A1:K'.$pos)->applyFromArray($styleArray);

        // Centra todas las celdas.
        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];
        $spreadsheet->getActiveSheet()->getStyle('A'.$posHeader.':K'.$pos)->getAlignment()->applyFromArray($styleArray);

        // Centra horizontalmente a la izquierda.
        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
        ];

        $spreadsheet->getActiveSheet()->getStyle('B'.$posFirstRow.':B'.$pos)->getAlignment()->applyFromArray($styleArray);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
    
        // Se guarda el excel y subida a la base de datos.
        $fristDayLastMonth = (new Carbon('first day of last month'))->hour(0)->minute(0)->second(0);
        $nameFile = $fristDayLastMonth->format('Y-m-d');
        Storage::disk('inboundaccesscharge')->put($nameFile.".xlsx", $content);

        $days = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $inboundAccessCharge = new InboundAccessCharge();
        $inboundAccessCharge->date = $fristDayLastMonth;
        $inboundAccessCharge->description = 'Cargos de acceso entrantes de '.$months[((int)$fristDayLastMonth->format('n') - 1)].' del '.$fristDayLastMonth->format('Y');
        $inboundAccessCharge->file_name = $nameFile;
        $inboundAccessCharge->save();

    }
}