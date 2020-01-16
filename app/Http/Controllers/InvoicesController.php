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
        dd($request);
        $now = (Carbon::now())->format('d/m/Y');
        $client = Client::find($request->id_client);
        $invoice_support_n = DB::connection('mysql')
            ->table('dummy')
            ->value('invoice_support_number');

        $data = [
            'data' => [
                'date' => $now,
                'invoice_support_n' => $invoice_support_n,
                'id_customer' => $client->id_customer,
                'customer' => $client->name,
                'address' => $client->address,
                'city' => $client->city,
                'country' => $client->country,
                'period' => '',
            ]
        ];

        $view = View::make('invoices.pdf', $data)->render();
        $pdf = PDF::loadHtml($view)->setPaper('tabloid');
        return $pdf->stream('invoice.pdf');
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

        if($request->vps > 2){
            $clients = DB::connection($database_name[$request->vps])
            ->table('clientsip')
            ->select(
                'id_client as IdClient', 
                DB::raw('0 as Type'), 
                'Login'
            )
            ->get();
        }else{
            $clients = DB::connection($database_name[$request->vps])
            ->table('invoiceclients')
            ->select(
                'IdClient', 
                'Type', 
                'Login'
            )
            ->where('IdClient','!=', '1')
            ->orWhere('Type','!=', '32')
            ->get();
        }

        return response()->json($clients);
    }
}