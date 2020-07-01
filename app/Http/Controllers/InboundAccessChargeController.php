<?php

namespace App\Http\Controllers;

use App\InboundAccessCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InboundAccessChargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Download excel
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $revenue = InboundAccessCharge::find($id);
        $file_name = $revenue->file_name.'.xlsx';
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'inboundaccesscharge'.DIRECTORY_SEPARATOR.$file_name;
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($fullpath, $file_name, $headers);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $sigue = false;
            $year = intval(request()->get('year')); // Si es null el valor es igual a 0

            if($year != 0){
                $sigue = true;
            }

            if($sigue){
                $date_start = Carbon::createFromFormat('Y-m-d', $year.'-01-01')->startOfMonth();
                $date_end = Carbon::createFromFormat('Y-m-d', $year.'-12-01')->endOfMonth();
            }else{
                $date_start = (new Carbon('first day of this year'))->startOfYear();
                $date_end = (new Carbon('last day of this year'))->endOfYear();
            }

            return datatables()->of(
                InboundAccessCharge::select('id', 'date', 'description')
                    ->whereBetween('date',
                        [
                            DB::raw('str_to_date("'.$date_start->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")'),
                            DB::raw('str_to_date("'.$date_end->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")')
                        ]
                    )
            )
            ->addColumn('action', 'actions.inboundaccesscharges')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('inboundaccesscharge.index');
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
        //
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
