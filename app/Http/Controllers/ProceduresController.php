<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProceduresController extends Controller
{
    public function index(){
        return view('procedures.index');
    }
}
