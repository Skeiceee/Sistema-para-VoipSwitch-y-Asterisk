<?php

namespace App\Http\Controllers;

use App\Portador;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;

class AccessChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portadores = Portador::select('id_port', 'portador')->get();
        return view('accesscharge.index', compact('portadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Get a collection of access charges according to date range and rate.
         *
         * @param int|string $time 1:normal, 2:reduced, 3:night
         * @return object
         */
        function queryAccessCharge(String $start_date, String $end_date, String $rate, $time = 'normal'){
            $base = DB::connection('asterisk')
                ->table('cdr as c')
                ->select(
                    DB::raw('sum(c.billsec) as segundos'),
                    DB::raw('count(c.userfield) as llamadas'),
                    DB::raw('sum(c.billsec) *  '.$rate.' as total')
                )
                ->whereBetween(
                    'c.calldate', 
                    [
                        DB::raw('str_to_date("'.$start_date.' 00:00:00", "%d/%m/%Y %H:%i:%s")'),
                        DB::raw('str_to_date("'.$end_date.'23:59:59", "%d/%m/%Y %H:%i:%s")')
                    ]
                )
                ->where('c.disposition', 'ANSWERED')
                ->where('c.userfield', '219')
                ->where('c.userfield', '!=' ,'');
            
            if($time == 'normal' || $time == 1){
                $base = $base->whereRaw('dayofweek(c.calldate) between 2 AND 6')
                    ->whereRaw('hour(c.calldate) between 9 AND 23')
                    ->get();
            }else if($time == 'reduced' || $time == 2){
                $base = $base->whereRaw('(dayofweek(c.calldate)= 7 OR dayofweek(c.calldate)= 1)')
                    ->whereRaw('hour(c.calldate) between 9 AND 23')
                    ->get();
            }else if($time == 'night' || $time == 3){
                $base = $base->whereRaw('hour(c.calldate) between 0 AND 8')
                    ->get();  
            }
            
            return $base;
        }



        $start_dates = $request->start_dates;
        $end_dates = $request->end_dates;
        $normal_rates = $request->normal_rates;
        $reduced_rates = $request->reduced_rates;
        $night_rates = $request->night_rates;

        for ($i = 0; $i < count($start_dates); $i++){ 
            $normal = queryAccessCharge($start_dates[$i], $end_dates[$i], $normal_rates[$i], 1);
            $reduced = queryAccessCharge($start_dates[$i], $end_dates[$i], $reduced_rates[$i], 2);
            $night = queryAccessCharge($start_dates[$i], $end_dates[$i], $night_rates[$i], 3);
            dd($normal, $reduced, $night);
        }
  
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
