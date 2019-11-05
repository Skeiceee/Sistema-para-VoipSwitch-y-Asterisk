<?php

namespace App\Http\Controllers;

use App\Portador;
use App\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            return datatables()->of(
                Rate::select(
                    'rates.id',
                    'rates.date',
                    'rates.rate_normal',
                    'rates.rate_reduced',
                    'rates.rate_night'
                )
            )
            ->addColumn('action', 'actions.rates')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('rates.index');
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
        $arr = explode(' - ', $request->date);
        $date = Carbon::createFromFormat('d/m/Y H:i:s',  '01/'.$arr[1].' 00:00:00');

        $rate = new Rate();
        
        $rate->date = Carbon::now();
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
