<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AsteriskController extends Controller
{
    public function status()
    {
        $status = DB::connection('asterisk')->table('report')->orderBy('id', 'desc')->limit(1)->first();

        if(isset($status)){
            $active = $status->active_calls;
            $processed = $status->processed_calls;
        }else{
            $active = 0;
            $processed = 0;
        }
    
        return response()->json([
            'active' => $active,
            'processed' => $processed
        ]);
    }

    public function sessionsmovistar()
    {
        $sessions_calls = DB::connection('asterisk')->table('sessions_movistar')->orderBy('id', 'desc')->limit(1)->first();

        if(isset($sessions_calls)){
            $movistar = $sessions_calls->movistar_calls;
            $entel = $sessions_calls->entel_calls;
            $other = $sessions_calls->other_calls;
        }else{
            $movistar = 0;
            $entel = 0;
            $other = 0;
        }
    
        return response()->json([
            'movistar' => $movistar,
            'entel' => $entel,
            'other' => $other
        ]);
    }
}
