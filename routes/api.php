<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('asterisk/status', function(){
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
});

Route::post('asterisk/sessions/movistar', function(){
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
});

