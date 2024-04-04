<?php

Route::group(['prefix' => 'modeles/', 'middleware' => 'roles','roles' => [4]], function () {
    Route::get('', 'ModeleController@index');
    Route::get('getDT', 'ModeleController@getDT');
    Route::get('getDT/{id}', 'ModeleController@getDT');
    Route::get('get/{id}','ModeleController@get');
    Route::get('getTab/{id}/{tab}','ModeleController@getTab');
    Route::get('add','ModeleController@formAdd');
    Route::post('add','ModeleController@add');
    Route::post('edit','ModeleController@edit');
    Route::get('delete/{id}','ModeleController@delete');
    Route::get('deleteElement/{id}','ModeleController@deleteElement');
    Route::get('getElts/{id}', 'ModeleController@getElts');
    Route::get('getElts/{id}/{selected}', 'ModeleController@getElts');
    Route::get('EditElts/{id}', 'ModeleController@EditElts');
    Route::get('addElement/{id}', 'ModeleController@addElement');
    Route::get('showChoiceValue/{id}', 'ModeleController@showChoiceValue');
    Route::post('saveElement', 'ModeleController@saveElement');
    Route::post('updateElement', 'ModeleController@updateElement');
    Route::post('editChoixElement', 'ModeleController@editChoixElement');
    Route::get('imprimerModele/{id}','ModeleController@imprimerModele');
    Route::get('getLignes/{id}','ModeleController@getLignes');
    Route::get('getParentLigne/{id}/{ligne}','ModeleController@getParentLigne');
    Route::get('getFilstLigne/{id}/{parent}','ModeleController@getFilstLigne');
});

Route::group(['prefix' => 'actes/', 'middleware' => 'roles','roles' => [4]], function () {
    Route::get('', 'ActeController@index');
    Route::get('getDT/{modele}/{date1}/{date2}', 'ActeController@getDT');
    Route::get('getDT/{modele}/{date1}/{date2}/{id}', 'ActeController@getDT');
    Route::get('get/{id}','ActeController@get');
    Route::get('getTab/{id}/{tab}','ActeController@getTab');
    Route::get('add','ActeController@formAdd');
    Route::post('add','ActeController@add');
    Route::post('edit','ActeController@edit');
    Route::get('delete/{id}','ActeController@delete');
    Route::get('getElts/{id}','ActeController@getElts');
    Route::get('exportactePDF/{id}','ActeController@exportactePDF');
});
