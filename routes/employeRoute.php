<?php

//  routes pour Employes
Route::group(['prefix' => 'employes/'/*, 'middleware' => 'roles'/*,'roles' => [1]*/], function () {
    Route::get('', 'EmployeController@index');
    Route::get('getDT/{genre}/{type_contrat}/{refSituationFamilliale}', 'EmployeController@getDT');
    Route::get('getDT/{genre}/{type_contrat}/{refSituationFamilliale}/{selected}/', 'EmployeController@getDT');
    Route::get('get/{id}', 'EmployeController@get');
    Route::get('getTab/{id}/{tab}', 'EmployeController@getTab');
    Route::get('add', 'EmployeController@formAdd');
    Route::post('add', 'EmployeController@add');
    Route::post('edit', 'EmployeController@edit');
    Route::post('editImage', 'EmployeController@editImage');
    Route::get('delete/{id}', 'EmployeController@delete');
    Route::get('openModalImage/{id}', 'EmployeController@openModalImage');
    Route::get('test', 'EmployeController@test');
    Route::post('exportEmployesPDF', 'EmployeController@exportEmployesPDF');
    Route::post('exportEmployesExcel', 'EmployeController@exportEmployesExcel');
    Route::post('updateImage', 'EmployeController@updateImage');
    Route::post('fiche_pdf', 'EmployeController@fiche_pdf');

    Route::get('experiences/getDT/{emp}', 'EmployeController@getDT_exp');
    Route::get('experiences/getDT/{emp}/{selected}', 'EmployeController@getDT_exp');
    Route::get('experiences/get/{emp}', 'EmployeController@get_exp');
    Route::get('experiences/getTab/{de}/{tab}', 'EmployeController@gettab_experience');
    Route::post('experiences/edite', 'EmployeController@edite_exp');
    Route::post('experiences/add', 'EmployeController@addExp');
    Route::get('experiences/add/{id}', 'EmployeController@formAddExp');
    Route::get('experiences/delete/{id}', 'EmployeController@deleteExp');
});

