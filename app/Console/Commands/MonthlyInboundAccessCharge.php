<?php

namespace App\Console\Commands;

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

        // Header
        $pos = 1;
        $posHeader = $pos;
        $sheet->setCellValue('A'.$pos, 'Desde '.$startFirstPeriod->format('d/m/Y').' hasta el '.$endFirstPeriod->day(24)->format('d/m/Y'));
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

            
        }

        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        $nameFile = 'excel_de_prueba';
        Storage::disk('inboundaccesscharge')->put($nameFile.".xlsx", $content);
    }
}
