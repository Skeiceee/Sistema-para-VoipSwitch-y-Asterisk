<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumerationStoreRequest;
use App\Numeration;
use App\Type;
use Illuminate\Http\Request;

class NumerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            return datatables()->of(
                Numeration::select(
                    'numerations.id',
                    'types.name as tipo_nombre',
                    'numerations.number as numero', 
                    'numerations.created_at as creacion', 
                    'numerations.updated_at as ult_modificacion',
                    'numerations.status as estado'
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        return view('numeration.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NumerationStoreRequest $request)
    {
        $start_numbers = $request->start_numbers;
        $end_numbers = $request->end_numbers;
        $types = $request->types;

        foreach ($start_numbers as $key => $start_number) {
            for ($number = $start_number; $number <= $end_numbers[$key]; $number++) { 
                $numeration = new Numeration();
                $numeration->number = $number;
                $numeration->type_id = $types[$key];
                $numeration->save();
            }
        }

        return redirect()->route('numeration.index')->with('status','Se han agregado los numeros exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $numeration = Numeration::find($id);
        
        if($numeration->status == 0){
            $numeration->delete();
            return redirect()->route('numeration.index')->with('status','Se ha eliminado el numero exitosamente.');
        }else{
            return redirect()->route('numeration.index')->with('error','No se ha eliminado, el numero se encuentra ocupado por un cliente.');
        }
    }
}
