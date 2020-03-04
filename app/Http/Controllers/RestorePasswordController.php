<?php

namespace App\Http\Controllers;

use App\Host;
use App\Subred;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RestorePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function restore(){
        return view('restore.index');
    }

    public function restoreUpload(Request $request){
        if($request->hasFile('file_csv')){
            $file = $request->file('file_csv');
            $sigue = true;
            $row = 1;
            if (($handle = fopen($file, "r")) !== false) {
                while ((($data = fgetcsv($handle, 1000, ",")) !== false) && $sigue == true) {
                    $num = count($data);
                    //Verificación de formato
                    if($row == 1){
                        if($num != 5){
                            $sigue = false;
                        }
                    }
                    //Asignación de la subred
                    if($row == 2){
                        if($num == 5){
                            $subred = new Subred();
                            $subred->name = $data[1];
                            $subred->ip = $data[2];
                            $subred->gateway = $data[3];
                            $subred->mask = $data[4];
                            $subred->save();
                        }else{
                            $sigue = false;
                        }
                    }
                    //Verificación de formato
                    if($row == 3){
                        if($num != 11){
                            $sigue = false;
                        }
                    }
                    //Asignacion de hosts de la subred
                    if($row >= 4){
                        if($num == 11){
                            $host = new Host();
                            $host->id_sub = $subred->id;

                            if($data[2] != ''){
                                $host->server = $data[2];
                            }
                            if($data[3] != ''){
                                $host->hostname = $data[3];
                            }
                            if($data[4] != ''){
                                $host->ipvmware = $data[4];
                            }
                            if($data[5] != ''){
                                $host->ip = $data[5];
                            }
                            if($data[6] != ''){
                                $host->username = $data[6];
                            }
                            if($data[7] != ''){
                                $host->password = Crypt::encrypt($data[7]);
                            }
                            if($data[8] != ''){
                                $host->port = $data[8];
                            }
                            if($data[9] != ''){
                                $host->obs = str_replace('/*/*', ',', $data[9]);
                            }
                            if($data[10] != ''){
                                $host->estado = $data[10];
                            }
                            
                            $host->save();
                        }else{
                            $sigue = false;
                        }
                    }

                    $row++;
                }
                fclose($handle);
            }
        }else{
            return $request;
        }

        if($sigue){
            return redirect()->route('restore.index')->with('status','Se ha importado la subred completa exitosamente.');
        }else{
            return redirect()->route('restore.index')->with('error','El formato del archivo esta corrupto.');
        }

    }
}
