<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subred;
use App\Host;
use IPv4;
use Illuminate\Support\Facades\DB;

class SubredesController extends Controller
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
                Subred::select('id', 'name', 'ip', 'gateway', 'mask')
            )
            ->addColumn('btn','actions.subredes')
            ->rawColumns(['btn'])
            ->make(true);
        }
        return view('subredes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subredes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:50',
            'ip' => 'required|ipv4',
            'gateway' => 'required|ipv4',
            'mask' => 'required|ipv4'
        ]);

        $subred = new Subred();
        $subred->name = $request->name;
        $subred->ip = $request->ip;
        $subred->gateway = $request->gateway;
        $subred->mask = $request->mask;

        if($subred->isMask()){
            $sub = new IPv4\SubnetCalculator($subred->ip, $subred->mask2cdr());
            
            $sigue = false;
            foreach ($sub->getAllHostIPAddresses() as $host_address) {
                if($subred->gateway == $host_address){
                    $sigue = true;
                }
            }

            if ($sigue) {
                $subred->save();
                foreach ($sub->getAllHostIPAddresses() as $host_address) {
                    if($subred->gateway != $host_address){
                        $host = new Host();
                        $host->id_sub = $subred->id;
                        $host->ip = $host_address;
                        $host->estado = 0;
                        $host->save();
                    }
                }
            }else{
                return redirect()->route('subredes.create')->withInput()->with('error','El campo puerta de enlace no se encuentra en el rango de Hosts.');
            }
        }else{
            return redirect()->route('subredes.create')->withInput()->with('error','La mascara de red no es valida.');
        }
        
        return redirect()->route('subredes.create')->with('status','Se ha agregado la subred exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subred = Subred::find($id);
        $sub = new IPv4\SubnetCalculator($subred->ip, $subred->mask2cdr());
        return view('subredes.show', compact('subred', 'sub'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subred = Subred::find($id);
        return view('subredes.edit', compact('subred'));
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
            'name' => 'string|min:1|max:50'
        ]);

        $subred = Subred::find($id);
        $subred->name = $request->name;
        $subred->save();
        return redirect()->route('subredes.index')->with('status','Se ha modificado la subred exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subred = Subred::find($id);
        DB::table('hosts')->where('id_sub', $subred->id)->delete();
        $subred->delete();
        return redirect()->route('subredes.index')->with('status','Se ha eliminado la subred exitosamente.');
    }
}