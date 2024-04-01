<?php

//  routes pour Employes
Route::group(['prefix' => 'courriers/'/*, 'middleware' => 'roles'/*,'roles' => [1]*/], function () {
    Route::get('', 'CourriersController@index');
    Route::get('getDT/{type}/{service}/{origine}/{niveau}/{dt_min}/{dt_max}', 'CourriersController@getDT');
    Route::get('getDT/{type}/{service}/{origine}/{niveau}/{dt_min}/{dt_max}/{selected}/', 'CourriersController@getDT');
    Route::get('get/{id}', 'CourriersController@get');
    Route::get('getTab/{id}/{tab}', 'CourriersController@getTab');
    Route::get('add', 'CourriersController@formAdd');
    Route::post('add', 'CourriersController@add');
    Route::post('edit', 'CourriersController@edit');
    Route::get('delete/{id}', 'CourriersController@delete');
    
	Route::post('export_pdf', 'CourriersController@export_pdf');
	Route::post('export_excel', 'CourriersController@export_excel');
    
});

