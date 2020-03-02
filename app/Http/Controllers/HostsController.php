<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Host;
use App\Subred;
use Illuminate\Support\Facades\Crypt;

class HostsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin')->except(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subredes = Subred::all();
        return view('Hosts.index', compact('subredes'));
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
        return view('Hosts.show', compact('host'));
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
        return view('Hosts.edit', compact('host'));
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

        return redirect()->route('hosts.index')->with('status','Se ha modificado el host exitosamente.');
    }

}