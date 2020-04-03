<?php

namespace App\Console\Commands;

use App\Mail\AlarmSessions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAlarmSessionsMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:alarmsessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an alarm when we exceed the session limit.';

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
        $sessions_calls = DB::connection('asterisk')->table('sessions_movistar')->orderBy('id', 'desc')->limit(1)->first();

        if(isset($sessions_calls)){
            list($movistar, $entel, $other) = [
                $sessions_calls->movistar_calls,
                $sessions_calls->entel_calls,
                $sessions_calls->other_calls
            ];
        }else{
            $movistar = $entel = $other = 0;
        }

        $total = $movistar + $entel + $other;

        $limite = 380;
        $porcentaje = 0.7;

        if($total >= $limite*$porcentaje){
            $data['to'] = [
                'administracion@vozdigital.cl',
                'noc@vozdigital.cl'
            ];
    
            $data['subject'] = 'Nuestras sesiones estan en llamas! 🔥';
    
            $data['total'] = $total;
    
            Mail::send(new AlarmSessions($data));
        }
    }
}
