<?php

Auth::routes();
Route::get('/', 'GeneralController@redirectRootPath');
Route::get('/datatables/spanish', 'GeneralController@datatablesSpanish')->name('datatables.spanish');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/consumos/download/{id}', 'RevenuesController@download')->name('revenues.download');
Route::resource('/consumos', 'RevenuesController', ['only' => ['index','create']])->names(['index' => 'revenues.index','create' => 'revenues.create']);

Route::get('/cargosdeacceso/download/{namefile}', 'AccessChargeController@download')->name('accesscharge.download');
Route::resource('/cargosdeacceso', 'AccessChargeController')->names(['index' => 'accesscharge.index', 'create' => 'accesscharge.create']);

Route::resource('/clientes', 'ClientsController')->names(
    [
        'index' => 'clients.index',
        'create' => 'clients.create',
        'store' => 'clients.store',
        'edit' => 'clients.edit',
        'update' => 'clients.update',
        'show' => 'clients.show'
    ]
);

Route::get('/numeracion', 'NumerationController@index')->name('numeration.index');

Route::get('/configuracion', 'ConfigurationController@index')->name('configuration.index');
Route::post('/configuracion/guardar', 'ConfigurationController@save')->name('configuration.save');

Route::get('/trafico', 'ReportsController@index')->name('traffic.index');
Route::post('/trafico/avg/hr/calls', 'ReportsController@avgperhrcalls')->name('reports.avgperhrcalls');
Route::post('/trafico/processed/calls', 'ReportsController@processedcalls')->name('reports.processedcalls');