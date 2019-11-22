<?php

namespace App\Http\Controllers;

use App\Portador;
use App\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $ido = (int) request()->ido;
            $year = (int) request()->year;
            $month = (int) request()->month;

            $strDate = $year.'-'.$month.'-01 00:00:00';
            
            $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $strDate, 'America/Santiago');
            $date_end = (Carbon::createFromFormat('Y-m-d H:i:s', $strDate, 'America/Santiago'))->endOfMonth();

            return datatables()->of(
                Rate::select(
                    'rates.id',
                    'rates.id_port',
                    'rates.start_date',
                    'rates.end_date',
                    'rates.rate_normal',
                    'rates.rate_reduced',
                    'rates.rate_night'
                )
                ->where('id_port', $ido)
                ->whereBetween(
                    'start_date',
                    [
                        DB::raw('str_to_date("'.$date_start->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")'),
                        DB::raw('str_to_date("'.$date_end->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")')
                    ]
                )
            )
            ->addColumn('action', 'actions.rates')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $portadores = Portador::select('id_port', 'portador')->get();

        return view('rates.index', compact('portadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $portadores = Portador::select('id_port', 'portador')->get();
        return view('rates.create', compact('portadores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = explode(' al ', $request->date);
        
        $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $arr[0].' 00:00:00');
        $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $arr[1].' 23:59:59');
        
        $rate = new Rate();
        
        $rate->start_date = $start_date;
        $rate->end_date = $end_date;
        $rate->id_port = $request->ido;
        $rate->rate_normal = $request->rate_normal;
        $rate->rate_reduced = $request->rate_reduced;
        $rate->rate_night = $request->rate_night;

        $rate->save();

        return redirect()->route('rates.create')->with('status', 'Se ha agregado correctamente la tarifa.');
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
