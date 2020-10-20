<?php

namespace App\Console\Commands;

use App\Interconnection;
use App\ProcessedCalls;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyProcessedCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:processedcalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Brings the number of calls processed from each interconnection.';

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
        $interconnections = Interconnection::all();
        $yesterday = Carbon::yesterday();

        foreach($interconnections as $interconnection){
            $yesterday_processed_call = DB::connection($interconnection->connection_no_strict_name)
                ->table('report')
                ->select(
                    'date',
                    'processed_calls'
                )
                ->whereBetween(
                    'date',
                    [
                        DB::raw('str_to_date("'.$yesterday->format('Y-m-d').' 00:00:00", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$yesterday->format('Y-m-d').' 23:59:59", "%Y-%m-%d %H:%i:%s")') 
                    ]
                )
                ->whereRaw('hour(date) = 23')
                ->whereRaw('minute(date) = 59')
                ->whereRaw('second(date) = 59')
                ->groupBy(
                    DB::raw('day(date)')
                )->first();
    
            $processed_calls = new ProcessedCalls();
    
            $processed_calls->date = $yesterday_processed_call->date;
            $processed_calls->processed = $yesterday_processed_call->processed_calls;
            $processed_calls->id_interconnection = $interconnection->id;
    
            $processed_calls->save();
        } 
    }
}
