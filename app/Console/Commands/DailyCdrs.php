<?php

namespace App\Console\Commands;

use App\Calls;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Voipswitch;
use Laracsv\Export;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DailyCdrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cdrs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily archive of CDRs';

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
        ini_set("memory_limit", -1);
        function getClients($conn_name, $version){
            if($version == '2.986.0.137'){
                return DB::connection($conn_name)
                ->table('invoiceclients')
                ->select('*')
                ->get();
            }else if ($version == '2.0.0.954'){
                return DB::connection($conn_name)
                ->table('clientsip')
                ->select('*')
                ->get(); 
            }    
        }

        function getCalls($voipswitch, $client, $startYesterday, $endYesterday){
            if($voipswitch->version == '2.986.0.137'){
                $calls = Calls::on($voipswitch->conn_name)
                ->where('id_client', $client->IdClient)
                ->where('client_type', $client->Type)
                ->whereBetween(
                    'call_start', 
                    [
                        DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
                ->whereRaw('(id_client != 1 OR client_type != 32)')
                ->get();
            }else if ($voipswitch->version == '2.0.0.954'){
                $calls = Calls::on($voipswitch->conn_name)
                ->where('id_client', $client->id_client)
                ->whereBetween(
                    'call_start',
                    [
                        DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
                ->get();
            }

            return $calls;
        }

        $startYesterday = Carbon::yesterday()->toDateTimeString();
        $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        $voipswitchs = Voipswitch::all();

        foreach ($voipswitchs as $voipswitch) {
            $clients = getClients($voipswitch->conn_name, $voipswitch->version);

            foreach ($clients as $client) {
                if($voipswitch->version == '2.986.0.137'){
                    $calls = getCalls($voipswitch, $client, $startYesterday, $endYesterday);
                    
                    $login = str_replace(' ', '_', $client->Login);
                }else if ($voipswitch->version == '2.0.0.954'){
                    $calls = getCalls($voipswitch, $client, $startYesterday, $endYesterday);

                    $login = str_replace(' ', '_', $client->login);
                }

                $csvExporter = new Export();
                $csvExporter->build($calls, ['call_start', 'call_end', 'called_number', 'duration', 'cost', 'tariffdesc']);
                $csvWriter = $csvExporter->getWriter();

                $login = str_replace(array('.', '-'), '', $login);

                $nameVps = str_replace(' ','', $voipswitch->name);
                $nameVps = str_replace(array('.', '-'), '_', $nameVps);

                $date = Carbon::yesterday()->format('d_m_Y');

                $nameFile = strtolower('cdrs_'.$login.'_'.$nameVps.'_'.$date.'.csv');
                Storage::disk('calls')->put($nameVps.'/'.$nameFile, $csvWriter->getContent());
            }
        }
    }
}