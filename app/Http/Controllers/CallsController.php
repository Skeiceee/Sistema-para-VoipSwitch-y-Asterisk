<?php

namespace App\Http\Controllers;

use App\Voipswitch;
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
}
