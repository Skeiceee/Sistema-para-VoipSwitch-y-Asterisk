<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyAveregeCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:averagecalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is responsible for bringing the average number of calls from the two interconnections';

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

        $avgCalls = DB::connection('asterisk.nostrict')
            ->table('report')
            ->distinct()
            ->select(
                'date',
                DB::raw('round(avg(active_calls)) as avg'),
                DB::raw('min(active_calls) as min'),
                DB::raw('max(active_calls) as max')
            )
            ->whereBetween(
                'date',
                [
                    DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                    DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                ]
            )
            ->groupBy(DB::raw('hour(date)'))
            ->get();

        $toInsert_asterisk_one = [];
        foreach($avgCalls as $record) {
            $record->id_interconnection = 1;
            $toInsert_asterisk_one[] = (array)$record;
        }

        $avgCalls = DB::connection('asterisk2.nostrict')
            ->table('report')
            ->distinct()
            ->select(
                'date',
                DB::raw('round(avg(active_calls)) as avg'),
                DB::raw('min(active_calls) as min'),
                DB::raw('max(active_calls) as max')
            )
            ->whereBetween(
                'date',
                [
                    DB::raw('str_to_date("'.$startYesterday.'", "%Y-%m-%d %H:%i:%s")'),
                    DB::raw('str_to_date("'.$endYesterday.'", "%Y-%m-%d %H:%i:%s")')
                ]
            )
            ->groupBy(DB::raw('hour(date)'))
            ->get();

        $toInsert_asterisk_two = [];
        foreach($avgCalls as $record) {
            $record->id_interconnection = 2;
            $toInsert_asterisk_two[] = (array)$record;
        }

        DB::connection('mysql')->table('average_calls')->insert($toInsert_asterisk_one);
        DB::connection('mysql')->table('average_calls')->insert($toInsert_asterisk_two);
    }
}