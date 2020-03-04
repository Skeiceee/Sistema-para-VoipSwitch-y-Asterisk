<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subred;
use App\Host;
use Carbon\Carbon;
use Laracsv\Export;
use Illuminate\Support\Facades\Crypt;

class BackupPasswordController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function backup(){
        $subredes = Subred::all();
        return view('Backup.index', compact('subredes'));
    }

    public function backupDownload(Request $request){
        $csvExporter = new Export();

        $subred = Subred::where('id',$request->id)->get();
        $hosts = Host::where('id_sub', $request->id)->get();

        $csvExporter->beforeEach(function ($hosts) {
            if($hosts->password){
                $hosts->password = Crypt::decrypt($hosts->password);
            }
            if($hosts->obs){
                $hosts->obs = str_replace(',', '/*/*', $hosts->obs);
            }
        });

        $now = Carbon::now();
        $csvExporter->build($subred, ['id', 'name', 'ip', 'gateway', 'mask']);
        $csvExporter->build($hosts, ['id', 'id_sub', 'server', 'hostname', 'ipvmware', 'ip', 'username', 'password', 'port', 'obs', 'estado'])->download('subred_'.$subred[0]->ip.'_time_'.$now->format('d_m_Y_His').'.csv');
    }
}