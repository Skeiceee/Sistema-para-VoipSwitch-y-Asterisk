<?php

namespace App\Http\Controllers;

use App\Client;
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
        $synergo = Client::find(1);
        dd($synergo->numerations()->get());
        if(request()->ajax()){
            return datatables()->of(
                Numeration::select(
                    'numerations.id', 
                    'clients.name as cliente', 
                    'numerations.description as descripcion', 
                    'numerations.path_documents', 
                    'numerations.created_at as creacion', 
                    'numerations.updated_at as ult_modificacion'
                )
                ->join('clients', 'numerations.id_client', 'clients.id')
            )
            ->addColumn('action', 'actions.numerations')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('numeration.index');
    }
}
