<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Subred;
use App\Host;

class HostsController extends Controller
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
            $subred = request()->get('subred');
            
            return datatables()->of(
                Host::select('id', 'server', 'hostname', 'ip')
                    ->where('id_sub', $subred)
            )
            ->addColumn('btn','actions.hosts')
            ->rawColumns(['btn'])
            ->make(true);
        }

        $subredes = Subred::all();
        return view('hosts.index', compact('subredes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $host = Host::find($id);
        return view('hosts.show', compact('host'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $host = Host::find($id);
        return view('hosts.edit', compact('host'));
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
        $request->validate([
            'username' => 'max:50',
            'password' => 'max:50',
            'port' => 'max:5',
            'server' => 'nullable|max:50',
            'hostname' => 'nullable|max:100',
            'ipvmware' => 'nullable|ipv4',
            'obs' => 'nullable|max:500',
        ]);

        $host = Host::find($id);
        $host->username = $request->username;
        $host->password = Crypt::encrypt($request->password);
        
        $host->port = $request->port;
        $host->server = $request->name_server;
        $host->hostname = $request->hostname;
        $host->ipvmware = $request->ipvmware;
        $host->obs = $request->obs;

        $host->estado = 1;
        $host->save();

        return redirect()->route('subredes.show', $host->id_sub)->with('status','Se ha modificado el host exitosamente.');
    }

}