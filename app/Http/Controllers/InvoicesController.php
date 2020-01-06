<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('invoices.index', compact('clients'));
    }
}
