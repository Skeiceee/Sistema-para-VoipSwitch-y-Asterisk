<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/', 'GeneralController@redirectRootPath');
Route::get('/datatables/spanish', 'GeneralController@datatablesSpanish')->name('datatables.spanish');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/consumos/download/{id}', 'RevenuesController@download')->name('revenues.download');
Route::resource('/consumos', 'RevenuesController', ['only' => ['index','create']])->names(['index' => 'revenues.index','create' => 'revenues.create']);

Route::get('/cargosdeacceso/download/{namefile}', 'AccessChargeController@download')->name('accesscharge.download');
Route::resource('/cargosdeacceso', 'AccessChargeController')->names(['index' => 'accesscharge.index', 'create' => 'accesscharge.create']);

Route::resource('/tarifas', 'RatesController')->names(
    [
        'index' => 'rates.index',
        'create' => 'rates.create',
        'store' => 'rates.store',
        'edit' => 'rates.edit',
        'update' => 'rates.update',
        'show' => 'rates.show',
        'destroy' => 'rates.destroy'
    ]
);

Route::get('/clientes/numeracion/{cliente}', 'ClientsController@numerations')->name('clients.numerations.add');
Route::post('/clientes/numeracion/{cliente}', 'ClientsController@saveNumerations')->name('clients.numerations.save');
Route::delete('/clientes/numeracion/{cliente}', 'ClientsController@deleteNumerations')->name('clients.numerations.delete');
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

Route::resource('/numeracion', 'NumerationController')->names(
    [
        'index' => 'numeration.index',
        'create' => 'numeration.create',
        'store' => 'numeration.store',
        'destroy' => 'numeration.destroy'
    ]
);

Route::get('/configuracion', 'ConfigurationController@index')->name('configuration.index');
Route::post('/configuracion/guardar', 'ConfigurationController@save')->name('configuration.save');

Route::get('/trafico', 'ReportsController@index')->name('traffic.index');
Route::post('/trafico/avg/hr/calls', 'ReportsController@avgperhrcalls')->name('reports.avgperhrcalls');
Route::post('/trafico/processed/calls', 'ReportsController@processedcalls')->name('reports.processedcalls');

Route::get('/facturas', 'InvoicesController@index')->name('invoices.index');
Route::post('/facturas', 'InvoicesController@download')->name('invoices.download');
Route::post('/facturas/buscar/cliente', 'InvoicesController@searchclient')->name('invoices.searchclient');
Route::post('/facturas/buscar/vps', 'InvoicesController@searchvps')->name('invoices.searchvps');

Route::get('/documentos/download/{id}', 'DocumentsController@download')->name('documents.download');
Route::resource('/documentos', 'DocumentsController')->names(
    [
        'index' => 'documents.index',
        'create' => 'documents.create',
        'store' => 'documents.store',
        'destroy' => 'documents.destroy'
    ]
);

Route::resource('/categorias/documentos', 'DocumentsCategoriesController')->names(
    [
        'index' => 'categories.documents.index',
        'create' => 'categories.documents.create',
        'store' => 'categories.documents.store',
        'destroy' => 'categories.documents.destroy'
    ]
);;