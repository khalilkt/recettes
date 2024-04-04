<?php

//  routes pour Employes
Route::group(['prefix' => 'archives/'/*, 'middleware' => 'roles'/*,'roles' => [1]*/], function () {
    Route::get('', 'ArchivesController@index');
    Route::get('getDT/{categor}/{service}/{dt_min}/{dt_max}/{etat}/{type}', 'ArchivesController@getDT');
    Route::get('getDT/{categor}/{service}/{dt_min}/{dt_max}/{etat}/{type}/{selected}/', 'ArchivesController@getDT');
    Route::get('get/{id}', 'ArchivesController@get');
    Route::get('getTab/{id}/{tab}', 'ArchivesController@getTab');
    Route::get('add', 'ArchivesController@formAdd');
    Route::post('add', 'ArchivesController@add');
    Route::post('edit', 'ArchivesController@edit');
    Route::get('delete/{id}', 'ArchivesController@delete');
    
    Route::get('getPage/{type}', 'ArchivesController@getPage');
    
    Route::post('export_pdf', 'ArchivesController@export_pdf');
    Route::post('export_excel', 'ArchivesController@export_excel');

    Route::get('reprendre/{id}', 'ArchivesController@reprendre');
    
});

