<?php

use Illuminate\Http\Request;

Route::post('asterisk/status', 'AsteriskController@status');
Route::post('asterisk/sessions/movistar', 'AsteriskController@sessionsmovistar');

