<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientSaveNumerationsRequest;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Numeration;
use App\Type;
use Illuminate\Http\Request;

class ClientsController extends Controller
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
                Client::select(
                    'clients.id', 
                    'clients.name as nombre', 
                    'clients.description as descripcion',
                    'clients.created_at as creacion',
                    'clients.updated_at as ult_modificacion' 
                )
            )
            ->addColumn('action', 'actions.clients')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientStoreRequest $request)
    {
        $client = new Client();

        $client->name = $request->name;
        $client->description = $request->description;

        $client->save();

        return redirect()->route('clients.index')->with('status', 'El cliente ha sido creado con exito.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        
        $numerations = $client->numerations()->get();
        $numerations = $numerations->sortBy('number');

        if($numerations->count() != 0){
            $intervals = [];
            $intervalIndex = 0;

            foreach ($numerations as $key => $numeration) {
                if(!isset($intervals[$intervalIndex][0])){
                    $intervals[$intervalIndex][] = $numeration->number;
                }

                if($key != 0){
                    if($numeration->number == ($numerations[$key - 1]->number + 1)){
                        $intervals[$intervalIndex][1] = $numeration->number;
                    }else{
                        $intervalIndex++;
                        $intervals[$intervalIndex][0] = $numeration->number;
                    }
                }
            }
        }

        return view('clients.show', compact('client', 'intervals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, $id)
    {
        $client = Client::find($id);

        $client->name = $request->name;
        $client->description = $request->description;

        $client->save();

        return redirect()->route('clients.index')->with('status', 'El cliente ha sido guardado con exito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //  
    }

    public function numerations(Request $request, $id)
    {
        $client = Client::find($id);
        $types = Type::all();
        return view('clients.numerations', compact('types', 'client'));
    }

    public function saveNumerations(ClientSaveNumerationsRequest $request, $id)
    {
        
        $client = Client::find($id);

        $start_numbers = $request->start_numbers;
        $end_numbers = $request->end_numbers;

        foreach ($start_numbers as $key => $start_number) {
            $end_number = $end_numbers[$key];
            
            for ($number = $start_number; $number <= $end_number; $number++) {
                $numeration = Numeration::where('number', $number)->first();

                if($numeration->status == 0){
                    $client->numerations()->save($numeration);

                    $numeration->status = 1;
                    $numeration->save();
                }else{
                    return redirect()->route('clients.numerations.add', $client->id)->with('status', 'Algo fallo :(');
                }
            }
        }

        $status = 'Los números han sigo agregados con exitosamente.';
        return redirect()->route('clients.numerations.add', $client->id)->with(compact('status'));
    }

    public function deleteNumerations(Request $request, $id)
    {
        return $request;
    }
}
