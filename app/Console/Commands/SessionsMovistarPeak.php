<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use function PHPSTORM_META\map;

class SessionsMovistarPeak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:sessionsmovistarpeak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a daily file with the peak of movistar sessions';

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
        $startYesterday = Carbon::yesterday()->toDateTimeString();
        $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);

        $pos = 1;
        $posHeader = $pos;

        $sheet->setCellValue('A'.$pos, 'Hora');
        $spreadsheet->getActiveSheet()->mergeCells('A'.$pos.':A'.($pos+1));
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$pos.':C'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472c4');

        $sheet->setCellValue('B'.$pos, 'Sesiones de Movistar');     
        $spreadsheet->getActiveSheet()->mergeCells('B'.$pos.':C'.$pos);
        
        $pos++;
        $sheet->setCellValue('B'.$pos, 'Promedio');
        $sheet->setCellValue('C'.$pos, 'Peak');
        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('B'.$pos.':C'.$pos)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5b9bd5');
        
        $styleArray = array(
            'font'  => array(
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true
        ));

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A'.$posHeader.':C'.$pos)->applyFromArray($styleArray);
        
        $sessions_avg_and_peak = DB::connection('asterisk.nostrict')
            ->table('sessions_movistar')
            ->select(
                DB::raw('hour(date) as hour'),
                DB::raw('avg(movistar_calls+entel_calls+other_calls) as avg'),
                DB::raw('max(movistar_calls+entel_calls+other_calls) as max')
            )
            ->whereBetween(
                'date', 
                [
                    $startYesterday,
                    $endYesterday
                ]
            )
            ->groupBy('hour')
            ->get();
        
        $posFirstRow = $pos;
        foreach ($sessions_avg_and_peak as $sessions_per_hour) {
            $pos++;

            $sheet->setCellValue('A'.$pos, $sessions_per_hour->hour);
            $sheet->setCellValue('B'.$pos, $sessions_per_hour->avg);
            $sheet->setCellValue('C'.$pos, $sessions_per_hour->max);
        }
        
        $spreadsheet->getActiveSheet()
            ->getStyle('B'.$posFirstRow.':C'.$pos)
            ->getNumberFormat()
            ->setFormatCode('_(* #,##0_);_(* -#,##0_);_(* "-"_);_(@_)');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(11);
        $sheet->getColumnDimension('C')->setWidth(11);

        // Agrega los bordes a las celdas.
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $spreadsheet->setActiveSheetIndexByName($sheet->getTitle())->getStyle('A1:C'.$pos)->applyFromArray($styleArray);

        // Centra todas las celdas.
        $styleArray = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ];
        $spreadsheet->getActiveSheet()->getStyle('A'.$posHeader.':C'.$pos)->getAlignment()->applyFromArray($styleArray);
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
    
        // Se guarda el excel y subida a la base de datos.
        $dateYesterday = Carbon::yesterday();
        $nameFile = $dateYesterday->format('Y-m-d');
        Storage::disk('sessionsmovistarpeak')->put($nameFile.".xlsx", $content);
    }
}
