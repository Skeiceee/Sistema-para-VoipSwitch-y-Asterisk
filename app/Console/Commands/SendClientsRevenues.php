<?php

namespace App\Console\Commands;

use App\Mail\ClientsRevenues;
use App\Voipswitch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendClientsRevenues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:clientsrevenues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the revenues to the different clients.';

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
        $yesterday = Carbon::yesterday();
        $startYesterday = Carbon::yesterday()->toDateTimeString();
        $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        $data['to'] = [
            'j.pavez@lftech.cl',
            'noc@vozdigital.cl'
        ];

        $days = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $data['subject'] = 'Consumos del '.$days[$yesterday->format('w')].', '.$yesterday->format('d').' de '.$months[((int)$yesterday->format('n') - 1)].' del '.$yesterday->format('Y');
        $data['revenues_date'] = 'Consumos del '.$days[$yesterday->format('w')].', '.$yesterday->format('d').' de '.$months[((int)$yesterday->format('n') - 1)].' del '.$yesterday->format('Y');

        $voipswitch = Voipswitch::find(5);
        $id_client = DB::connection($voipswitch->conn_name)
            ->table('clientsip as cs')
            ->select('cs.id_client')
            ->where('login', 'Conectados')
            ->where('id_client', '29')
            ->get();

        $revenue = DB::connection($voipswitch->conn_name)->table('calls as c')
            ->select(
                DB::raw('round((sum(c.duration)/60)) as minutes'),
                DB::raw('count(*) as calls')
            )
            ->join('clientsip as cs', 'c.id_client', 'cs.id_client')
            ->where('c.id_client', 29)
            ->whereBetween(
                'c.call_start', 
                [
                    DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                    DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                ]
            )
            ->groupBy('c.id_client', 'cs.login')
            ->first();

            if($revenue){
                $data['calls'] = $revenue->calls;
                $data['minutes'] = $revenue->minutes;
            }else{
                $data['calls'] = 0;
                $data['minutes'] = 0;
            }

        Mail::send(new ClientsRevenues($data));
    }
}
