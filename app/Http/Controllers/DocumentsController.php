<?php

namespace App\Http\Controllers;

use App\Category;
use App\Document;
use App\Http\Requests\DocumentsStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            return datatables()->of(
                Document::select(
                    'documents.id',
                    'documents_categories.name as name_category',
                    'documents.name as nombre',
                    'documents.description as descripcion',
                )
                ->join('documents_categories', 'documents.id_category', 'documents_categories.id')
            )
            ->addColumn('action', 'actions.documents')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('documents.create', compact('categories'));
    }

    public function download($id)
    {
        $document = Document::find($id);
        $file_name = $document->path;
        $name_for_download = $document->name_for_download.'.'.$document->extension;
        $fullpath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'documents'.DIRECTORY_SEPARATOR.$file_name;
        return response()->download($fullpath, $name_for_download);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentsStoreRequest $request)
    {
        $document = new Document();
        
        $extension = $request->file->extension();
        $original_name = basename($request->file->getClientOriginalName(), ".".$extension);
        $path = basename($request->file->store('documents'));
        
        $document->name = $request->name;
        $document->id_category = $request->category;
        $document->description = $request->description;
        $document->extension = $extension;
        $document->name_for_download = $original_name;
        $document->path = $path;
        
        $document->save();

        return redirect()->route('documents.create')->with('status','Se ha agregado el documento exitosamente.');
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
