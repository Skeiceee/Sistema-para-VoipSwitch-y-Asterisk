<?php

namespace App\Http\Controllers;

use App\Client;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('invoices.index', compact('clients'));
    }

    public function download(Request $request)
    {
        $client = Client::find($request->id_client);
        $data = [
            'data' => [
                'date' => '',
                'invoice_support_n' => '',
                'id_customer' => '',
                'customer' => $client->name,
                'address' => $client->address,
                'city' => $client->city,
                'country' => $client->country,
                'period' => '',
            ]
        ];

        // dd($client, $request);
        $pdf = PDF::loadView('invoices.pdf', $data)->setPaper('tabloid');
        return $pdf->stream('invoice.pdf');
    }
}