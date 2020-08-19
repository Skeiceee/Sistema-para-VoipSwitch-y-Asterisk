<?php

namespace App\Http\Controllers;

use App\SessionsMovistar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionsMovistarController extends Controller
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

    public function download($id)
    {
        $session_movistar = SessionsMovistar::find($id);
        $file_name = $session_movistar->file_name.'.xlsx';
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'sessionsmovistar'.DIRECTORY_SEPARATOR.$file_name;
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($fullpath, $file_name, $headers);
    }

    public function index()
    {
        if(request()->ajax()){
            $sigue = false;
            $month = intval(request()->get('month')); // Si es null el valor es igual a 0
            $year = intval(request()->get('year')); // Si es null el valor es igual a 0

            if($month != 0 and $year != 0){
                foreach (range(1, 12) as $number) {
                    if($month == $number){
                        $sigue = true;
                    }
                }
            }
            
            if($sigue){
                $date_start = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->startOfMonth();
                $date_end = Carbon::createFromFormat('Y-m-d', $year.'-'.$month.'-01')->endOfMonth();
            }else{
                $date_start = (new Carbon('first day of this month'))->startOfMonth();
                $date_end = (new Carbon('first day of this month'))->endOfMonth();
            }

            return datatables()->of(
                SessionsMovistar::select('id', 'date', 'description')
                    ->whereBetween('date',
                        [
                            DB::raw('str_to_date("'.$date_start->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")'),
                            DB::raw('str_to_date("'.$date_end->format('Y-m-d H:i:s').'", "%Y-%m-%d %H:%i:%s")')
                        ]
                    )
            )
            ->addColumn('action', 'actions.sessionsmovistar')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            
        }

        return view('sessionsmovistar.index');
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
