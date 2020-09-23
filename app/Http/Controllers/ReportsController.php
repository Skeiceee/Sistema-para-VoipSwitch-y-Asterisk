<?php

namespace App\Http\Controllers;

use App\Interconnection;
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
        $interconnections = Interconnection::all();
        return view('traffic.index', compact('interconnections'));
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

        set_time_limit(0);
        $interconnection = Interconnection::find($request->itx);

        $strDate = $year.'-'.$month.'-01';
        $startDay = (new Carbon($strDate))->day;
        $endDay = (new Carbon($strDate))->endOfMonth()->day;

        function avgsPerHour($year, $month, $day, $id_interconnection){
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
                ->where('id_interconnection', $id_interconnection)
                ->get();
        }

        $datasets = [];
        for ($day = $startDay; $day <= $endDay; $day++) {
            $dataset = []; $avgs = [];
            $avgsPerHour = avgsPerHour($year, $month, $day, $interconnection->id);
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

    public function processedcalls(Request $request)
    {   
        set_time_limit(0);
        $interconnection = Interconnection::find($request->itx);

        if(is_null($request->month) and is_null($request->year)){
            $start = Carbon::now()->firstOfMonth();
            $year = $start->year;
            $month = $start->month;
        }else{
            $year = $request->year;
            $month = $request->month;
        }

        $strDate = $year.'-'.$month.'-01';
        $startDay = (new Carbon($strDate));
        $endDay = (new Carbon($strDate))->endOfMonth();

        $query = DB::connection($interconnection->connection_no_strict_name)
            ->table('report')
            ->select(
                'date',
                'processed_calls'
            )
            ->whereBetween(
                'date',
                [
                    DB::raw('str_to_date("'.$startDay->format('Y-m-d').' 00:00:00", "%Y-%m-%d %H:%i:%s")'),
                    DB::raw('str_to_date("'.$endDay->format('Y-m-d').' 23:59:59", "%Y-%m-%d %H:%i:%s")') 
                ]
            )
            ->whereRaw('hour(date) = 23')
            ->whereRaw('minute(date) = 59')
            ->whereRaw('second(date) = 59')
            ->groupBy(
                DB::raw('day(date)')
            )->get();

            return response()->json($query);
    }
}