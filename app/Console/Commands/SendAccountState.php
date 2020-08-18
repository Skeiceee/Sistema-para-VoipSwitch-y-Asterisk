<?php

namespace App\Console\Commands;

use App\Mail\AccountState;
use App\Voipswitch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAccountState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:accountstate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the account state to the different clients.';

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
        $now = Carbon::now();

        $data['to'] = [
            'mriquelme@contactpoint.cl',
            'noc@vozdigital.cl'
        ];

        $days = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $message = 'Saldo actual del '.$days[$now->format('w')].', '.$now->format('d').' de '.$months[((int)$now->format('n') - 1)].' del '.$now->format('Y');
        $data['subject'] = $message;
        $data['revenues_date'] = $message;

        $voipswitch = Voipswitch::find(3);
        $client = DB::connection($voipswitch->conn_name)
            ->table('clientsip as cs')
            ->select('cs.id_client', 'cs.login', 'cs.account_state')
            ->where('login', 'ContacPoint')
            ->where('id_client', '25')
            ->first();

        $data['login'] = $client->login;
        $data['account_state'] = $client->account_state;

        Mail::send(new AccountState($data));
    }
}
