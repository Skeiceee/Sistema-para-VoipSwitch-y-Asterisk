<?php

namespace App\Http\Controllers;

use App\Client;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
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
        $client = Client::find($request->id_client);
        $data = [
            'data' => [
                'date' => '',
                'invoice_support_n' => '',
                'id_customer' => $client->id_customer,
                'customer' => $client->name,
                'address' => $client->address,
                'city' => $client->city,
                'country' => $client->country,
                'period' => '',
            ]
        ];

        // dd($client, $request);
        // return view('invoices.pdf', $data);
        $view = View::make('invoices.pdf', $data)->render();
        $pdf = PDF::loadHtml($view)->setPaper('tabloid');
        return $pdf->stream('invoice.pdf');
    }
}