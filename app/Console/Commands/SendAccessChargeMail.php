<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccessChargeMail;
use App\User;
use Carbon\Carbon;

class SendAccessChargeMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:accesscharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send access charges from the previous day.';

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

        $data['file_date'] = $yesterday->format('Y-m-d');

        $data['to'] = [
            'administracion@vozdigital.cl',
            'noc@vozdigital.cl'
        ];

        $days = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $months = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $data['subject'] = 'Consumos del '.$days[$yesterday->format('w')].', '.$yesterday->format('d').' de '.$months[((int)$yesterday->format('n') - 1)].' del '.$yesterday->format('Y');
        $data['revenues_date'] = 'Consumos del '.$days[$yesterday->format('w')].', '.$yesterday->format('d').' de '.$months[((int)$yesterday->format('n') - 1)].' del '.$yesterday->format('Y');

        Mail::send(new AccessChargeMail($data));
    }
}
