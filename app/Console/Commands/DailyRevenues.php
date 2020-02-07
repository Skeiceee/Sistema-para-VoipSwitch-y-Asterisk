<?php

namespace App\Console\Commands;

use App\DailyRevenue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyRevenues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:revenues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load consumption into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected static function revenuesCondellQuery(String $nameDataBase, String $start, String $end){
        return DB::connection('condell.'.$nameDataBase)
            ->table('calls as c')
            ->select(
                'c.id_client as id_client', 
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startYesterday = Carbon::yesterday()->toDateTimeString();
        $endYesterday =  Carbon::yesterday()->hour(23)->minute(59)->second(59)->toDateTimeString();

        $revenuesArgentina = DB::connection('argentina')
            ->table('calls as c')
            ->select(
                'c.id_client',
                'i.Login',
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
            ->groupBy('c.id_client', 'c.client_type', 'i.Login')
            ->orderBy('sale', 'desc')
            ->get();

        $revenuesWholesale = DB::connection('wholesale')
            ->table('calls as c')
            ->select(
                'c.id_client',
                'i.Login',
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
            ->groupBy('c.id_client', 'c.client_type', 'i.Login')
            ->orderBy('sale', 'desc')
            ->get();
    
        $revenuesSistek = DB::connection('asterisk')
            ->table('cdr as c')
            ->select(
                DB::raw('"0" as id_client'),
                DB::raw('"Sistek" as Login'),
                DB::raw('sum( c.billsec )/60 as minutes_real'),
                DB::raw('sum( c.billsec ) as seconds_real_total'),
                DB::raw('sum( c.billsec )/60 as minutes_effective'),
                DB::raw('sum( c.billsec ) as seconds_effective_total'),
                DB::raw('sum( c.billsec )/60*7 as sale'),
                DB::raw('"0" as cost')
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

        $revenuesHeavyuser = $this::revenuesCondellQuery('heavyuser', $startYesterday, $endYesterday);
        $revenuesSynergo = $this::revenuesCondellQuery('synergo', $startYesterday, $endYesterday);
        $revenuesRetail = $this::revenuesCondellQuery('retail', $startYesterday, $endYesterday);

        
        foreach ($revenuesArgentina as $revenue) {
            DailyRevenue::create([
                'id_client' => $revenue->id_client,
                'date' => Carbon::yesterday(),
                'login' => $revenue->Login,
                'minutes_real' => $revenue->minutes_real,
                'seconds_real_total' => $revenue->seconds_real_total,
                'minutes_effective' => $revenue->minutes_effective,
                'seconds_effective_total' => $revenue->seconds_effective_total,
                'sale' => $revenue->sale,
                'cost' => $revenue->cost
            ]);
        }
    }
}
