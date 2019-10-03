<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(request()->ajax()){
            
        }
        return view('numeration.index');
    }
}
