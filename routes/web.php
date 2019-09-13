<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/consumos/download/{id}', 'RevenuesController@download')->name('revenues.download');
Route::resource('/consumos', 'RevenuesController', ['only' => ['index','create']])->names(['index' => 'revenues.index','create' => 'revenues.create']);

Route::get('/cargosdeacceso/download/{namefile}', 'AccessChargeController@download')->name('accesscharge.download');
Route::resource('/cargosdeacceso', 'AccessChargeController')->names(['index' => 'accesscharge.index', 'create' => 'accesscharge.create']);

Route::get('/configuracion', 'ConfigurationController@index')->name('configuration.index');
Route::post('/configuracion/guardar', 'ConfigurationController@save')->name('configuration.save');

Route::get('/datatables/spanish', function(){
    return response()->json([
        'sProcessing' => 'Procesando...',
        'sLengthMenu' => 'Mostrar _MENU_ registros',
        'sZeroRecords' => 'No se encontraron resultados',
        'sEmptyTable' => 'Ningún dato disponible en esta tabla',
        'sInfo' => 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
        'sInfoEmpty' => 'Mostrando registros del 0 al 0 de un total de 0 registros',
        'sInfoFiltered' => '(filtrado de un total de _MAX_ registros)',
        'sInfoPostFix' => '',
        'sSearch' => 'Buscar:',
        'sUrl' => '',
        'sInfoThousands' => ',',
        'sLoadingRecords' => 'Cargando...',
        'oPaginate' => [
            'sFirst' => 'Primero',
            'sLast' => 'Último',
            'sNext' => 'Siguiente',
            'sPrevious' => 'Anterior'  
        ],
        'pAria' => [
            'sSortAscending' => 'Activar para ordenar la columna de manera ascendente',
            'sSortDescending' => 'Activar para ordenar la columna de manera descendente'
        ]
    ]);
})->middleware('auth')->name('datatables.spanish');