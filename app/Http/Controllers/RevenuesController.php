<?php

namespace App\Http\Controllers;

use App\Revenue;
use Illuminate\Http\Request;

class RevenuesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Download excel
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $revenue = Revenue::find($id);
        $file_name = $revenue->file_name.'.xlsx';
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'revenues'.DIRECTORY_SEPARATOR.$file_name;
        $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        return response()->download($fullpath, $file_name, $headers);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            return datatables()->of(Revenue::select('id', 'date', 'description'))
                ->addColumn('action', 'actions.revenues')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('revenues.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
