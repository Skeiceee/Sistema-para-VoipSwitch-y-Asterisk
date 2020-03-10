<?php

namespace App\Http\Controllers;

use App\Voipswitch;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZanySoft\Zip\Zip as Zip;

class CallsController extends Controller
{
    public function index(){
        $voipswitchs = Voipswitch::all();
        return view('calls.index', compact('voipswitchs'));
    }

    public function searchvps(Request $request)
    {
        $voipswitch = Voipswitch::find($request->vps);

        if(strpos($voipswitch->conn_name,'condell') === false){
            $clients = DB::connection($voipswitch->conn_name)
                ->table('invoiceclients')
                ->select(
                    'IdClient', 
                    'Type', 
                    'Login'
                )
                ->where('IdClient','!=', '1')
                ->orWhere('Type','!=', '32')
                ->get();
        }else{
            $clients = DB::connection($voipswitch->conn_name)
                ->table('clientsip')
                ->select(
                    'id_client as IdClient', 
                    DB::raw('0 as Type'), 
                    'Login'
                )
                ->get();
        }

        return response()->json($clients);
    }

    public function download(Request $request){
        $id_vps = $request->id_vps;

        list($id_client, $type) = explode(';', $request->vps_client);

        $voipswitch = Voipswitch::find($id_vps);

        if($voipswitch->version == '2.986.0.137'){
            $client = DB::connection($voipswitch->conn_name)
                ->table('invoiceclients')
                ->select(
                    'IdClient', 
                    'Type', 
                    'Login'
                )
                ->where('IdClient', $id_client)
                ->where('Type', $type)
                ->where('IdClient','!=', '1')
                ->orWhere('Type','!=', '32')
                ->first();

            $login = str_replace(' ', '_', $client->Login);
        }else if($voipswitch->version == '2.0.0.954'){
            $client = DB::connection($voipswitch->conn_name)
                ->table('clientsip')
                ->select(
                    'id_client as IdClient',
                    'login'
                )
                ->where('id_client', $id_client)
                ->first();

            $login = str_replace(' ', '_', $client->login);
        }

        $login = str_replace(array('.', '-'), '', $login);

        $nameVps = str_replace(' ','', $voipswitch->name);
        $nameVps = str_replace(array('.', '-'), '_', $nameVps);

        $partNameFile = strtolower('cdrs_'.$login.'_'.$nameVps.'_');

        list($str_start_date, $str_end_date) = explode(' al ', $request->date);

        $start_date = Carbon::createFromFormat('d/m/Y H:i:s',  $str_start_date.' 00:00:00');
        $end_date = Carbon::createFromFormat('d/m/Y H:i:s',  $str_end_date.' 00:00:00');

        $period = new CarbonPeriod($start_date, '1 day', $end_date);

        $zip = Zip::create(join(DIRECTORY_SEPARATOR, [storage_path('app'), 'cdrs.zip']));
        foreach ($period as $dt) {
            $date = $dt->format('d_m_Y');
            $nameFile = join(DIRECTORY_SEPARATOR, [$nameVps, $partNameFile.$date.'.csv']);
            $pathFile = join(DIRECTORY_SEPARATOR, [storage_path('app'), 'calls', $nameFile]);

            dd($pathFile, $nameFile);
            if (file_exists($pathFile)) {
                $zip->add($pathFile);
                $zip->listFiles();
            }
        }
        $zip->close();
        
        //Limpia el buffer.
        ob_clean();
        ob_end_flush();

        $pathZip = storage_path('app/cdrs.zip');
        return response()->download($pathZip, 'cdrs.zip', 
            [
                'Content-Type: application/octet-stream',
                'Content-Length: '. filesize($pathZip)
            ]
        )->deleteFileAfterSend(true);
    }
}
