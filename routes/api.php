<?php

use Illuminate\Http\Request;

Route::post('asterisk/status', 'AsteriskController@status');
Route::post('asterisk/sessions/movistar', 'AsteriskController@sessionsmovistar');

Route::post('asterisk2/status', 'AsteriskController@asterisk2_status');
Route::post('asterisk2/sessions/movistar', 'AsteriskController@asterisk2_sessionsmovistar');