<?php

namespace App\Http\Controllers;

use App\Client;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class InvoicesController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('invoices.index', compact('clients'));
    }

    public function download(Request $request)
    {
        $now = (Carbon::now())->format('d/m/Y');
        $client = Client::find($request->id_client);

        $invoice_support_n = DB::connection('mysql')
            ->table('dummy')
            ->value('invoice_support_number');

        $date = $request->date;
        $dates = explode(' al ', $date);
        $start_date = trim($dates[0]).' 00:00:00';
        $end_date = trim($dates[1]).' 23:59:59';

        $vps_client = explode(';', $request->vps_client);

        $database_name = [
            1 => 'argentina',
            2 => 'wholesale',
            3 => 'condell.heavyuser',
            4 => 'condell.synergo',
            5 => 'condell.retail',
        ];

        $vps = $request->vps;
        if($vps <= 2){
            $trunks = DB::connection($database_name[$vps])
                ->table('calls as c')
                ->select(
                    'tariffdesc as trunk', 
                    DB::raw('count(id_call) as processed_calls'),
                    DB::raw('format(sum(effective_duration), 0) as effective_duration'),
                    DB::raw('format(sum(cost), 2) as amount')
                )
                ->join('invoiceclients as ic', 'ic.IdClient', 'c.id_client')
                ->whereBetween(
                    'c.call_start',
                    [
                        DB::raw('str_to_date("'.$start_date.'", "%d/%m/%Y %H:%i:%s")'),
                        DB::raw('str_to_date("'.$end_date.'", "%d/%m/%Y %H:%i:%s")')
                    ]
                )
                ->where('ic.Type', '=', DB::raw('c.client_type'))
                ->where('ic.IdClient', $vps_client[0])
                ->where('ic.Type', $vps_client[1])
                ->groupBy('trunk')
                ->get();
        }else{
            
        }

        $data = [
            'data' => [
                'date' => $now,
                'invoice_support_n' => $invoice_support_n,
                'id_customer' => $client->id_customer,
                'customer' => $client->name,
                'address' => $client->address,
                'city' => $client->city,
                'country' => $client->country,
                'period' => $date,
                'trunks' => $trunks
            ]
        ];

        $view = View::make('invoices.pdf', $data)->render();
        $pdf = PDF::loadHtml($view)->setPaper('tabloid');
        return $pdf->download('invoice.pdf');
    }

    public function searchclient(Request $request)
    {
        $client = Client::find($request->id_client);

        $data = [
            'id_customer' => $client->id_customer,
            'address' => $client->address,
            'city' => $client->city,
            'country' => $client->country,
        ];

        return response()->json($data);
    }

    public function searchvps(Request $request)
    {
        $database_name = [
            1 => 'argentina',
            2 => 'wholesale',
            3 => 'condell.heavyuser',
            4 => 'condell.synergo',
            5 => 'condell.retail',
        ];

        $vps = $request->vps;
        if($vps < 2){
            $clients = DB::connection($database_name[$vps])
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
            $clients = DB::connection($database_name[$vps])
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