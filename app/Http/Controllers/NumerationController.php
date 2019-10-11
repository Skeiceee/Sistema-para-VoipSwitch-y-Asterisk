<?php

namespace App\Http\Controllers;

use App\Numeration;
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
            return datatables()->of(
                Numeration::select(
                    'id', 
                    'id_client as cliente', 
                    'description as descripcion', 
                    'path_documents', 
                    'created_at as creacion', 
                    'updated_at as ult_modificacion'
                )
            )
            ->addColumn('action', 'actions.numerations')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('numeration.index');
    }
}
