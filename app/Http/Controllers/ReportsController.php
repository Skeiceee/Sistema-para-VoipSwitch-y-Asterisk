<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display of reports
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.index');
    }

    public function avgperhrcalls(Request $request)
    {
        if(is_null($request->month) and is_null($request->year)){
            $start = Carbon::now()->firstOfMonth();
            $year = $start->year;
            $month = $start->month;
        }else{
            $year = $request->year;
            $month = $request->month;
        }

        $strDate = $year.'-'.$month.'-01';
        $startDay = (new Carbon($strDate))->day;
        $endDay = (new Carbon($strDate))->endOfMonth()->day;

        function avgsPerHour($year, $month, $day){
            $strDay = $year.'-'.$month.'-'.$day;
            return $avgDay = DB::connection('mysql')
                ->table('average_calls')
                ->select('avg')
                ->whereBetween(
                    'date',
                    [
                        DB::raw('str_to_date("'.$strDay.' 00:00:00", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$strDay.' 23:59:59", "%Y-%m-%d %H:%i:%s")') 
                    ]
                )
                ->get();
        }

        $datasets = [];
        for ($day = $startDay; $day <= $endDay; $day++) {
            $dataset = []; $avgs = [];
            $avgsPerHour = avgsPerHour($year, $month, $day);
            $dataset = ['label' => $day.'/'.$month.'/'.$year];
            foreach ($avgsPerHour as $key => $avgPerHour) {
                $avgs = $avgs + [$key => $avgPerHour->avg];
            }
            $dataset = $dataset + ['data' => $avgs];
            $datasets = $datasets + [($day-1) => $dataset];
        }
        // dd($datasets);
        // Fomarto para que imprima bien el grafico
        return response()->json($datasets);
    }

    public function maxAvgCalls(Request $request)
    {
        //
    }
}