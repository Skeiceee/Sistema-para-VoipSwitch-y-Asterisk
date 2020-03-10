<?php

namespace App\Http\Controllers;

use App\Voipswitch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallsController extends Controller
{
    public function index(){
        $voipswitchs = Voipswitch::all();
        return view('calls.index', compact('voipswitchs'));
    }

    public function searchvps(Request $request)
    {
        $voipswitch = Voipswitch::find($request->vps);

        if(strpos($voipswitch->conn_name,'condell') === false){
            $clients = DB::connection($voipswitch->conn_name)
                ->table('invoiceclients')
                ->select(
                    'IdClient', 
                    'Type', 
                    'Login'
                )
                ->where('IdClient','!=', '1')
                ->orWhere('Type','!=', '32')
                ->get();
        }else{
            $clients = DB::connection($voipswitch->conn_name)
                ->table('clientsip')
                ->select(
                    'id_client as IdClient', 
                    DB::raw('0 as Type'), 
                    'Login'
                )
                ->get();
        }

        return response()->json($clients);
    }

    public function download(Request $request){
        $id_vps = $request->id_vps;

        list($id_client, $type) = explode(';', $request->vps_client);
        list($str_start_date, $str_end_date) = explode(' al ', $request->date);

        $start_date = Carbon::createFromFormat('d/m/Y H:i:s',  $str_start_date.' 00:00:00');
        $end_date = Carbon::createFromFormat('d/m/Y H:i:s',  $str_end_date.' 00:00:00');
        
        dd($start_date, $end_date);

        $voipswitch = Voipswitch::find($id_vps);

        if(strpos($voipswitch->conn_name,'condell') === false){
            $client = DB::connection($voipswitch->conn_name)
                ->table('invoiceclients')
                ->select(
                    'IdClient', 
                    'Type', 
                    'Login'
                )
                ->where('IdClient', $id_client)
                ->where('Type', $type)
                ->where('IdClient','!=', '1')
                ->orWhere('Type','!=', '32')
                ->first();
        }else{
            $client = DB::connection($voipswitch->conn_name)
                ->table('clientsip')
                ->select(
                    'id_client as IdClient', 
                    DB::raw('0 as Type'), 
                    'Login'
                )
                ->where('id_client', $id_client)
                ->first();
        }


        dd($request, $voipswitch, $client);
    }
}
