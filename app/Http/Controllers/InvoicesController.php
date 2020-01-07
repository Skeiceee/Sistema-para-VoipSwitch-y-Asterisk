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

    public function download()
    {
        $pdf = PDF::loadView('invoices.pdf')->setPaper('tabloid');
        return $pdf->download('invoice.pdf');
    }
}