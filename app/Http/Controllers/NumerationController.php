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
        if(request()->ajax()){
            return datatables()->of(
                Numeration::select(
                    'numerations.id',
                    'types.name as tipo_nombre',
                    'numerations.number as numero', 
                    'numerations.created_at as creacion', 
                    'numerations.updated_at as ult_modificacion'
                )
                ->join('types', 'numerations.type_id', 'types.id')
            )
            ->addColumn('action', 'actions.numerations')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('numeration.index');
    }
}
