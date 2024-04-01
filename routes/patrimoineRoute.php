<?php

Route::group(['prefix' => 'equipements/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('', 'EquipementController@index');
    Route::get('getDT', 'EquipementController@getDT');
    Route::get('getDTE/{type}/{secteur}/{localite}', 'EquipementController@getDTE');
    Route::get('getDTE/{type}/{secteur}/{localite}/{id}', 'EquipementController@getDTE');
    Route::get('getDT/{id}', 'EquipementController@getDT');
    Route::get('getEquipements', 'EquipementController@getEquipements');
    Route::get('getElts/{id}', 'EquipementController@getElts');
    Route::get('getElt/{id}', 'EquipementController@getElt');
    Route::get('getEltexistent/{id}', 'EquipementController@getEltexistent');
    Route::get('get/{id}','EquipementController@get');
    Route::get('getTab/{id}/{tab}','EquipementController@getTab');
    Route::get('add','EquipementController@formAdd');
    Route::get('visualiserCommune','EquipementController@visualiserCommune');
    Route::post('add','EquipementController@add');
    Route::post('edit','EquipementController@edit');
    Route::post('editType','EquipementController@editType');
    Route::get('delete/{id}','EquipementController@delete');
    Route::get('suiviHistorique/{id}', 'EquipementController@suiviHistorique');
    Route::get('suiviHistoriqueBt/{id}', 'EquipementController@suiviHistoriqueBt');
    Route::get('modifierTypeEquipement/{id}', 'EquipementController@modifierTypeEquipement');
    Route::get('gethistoriques/{id}', 'EquipementController@gethistoriques');
    Route::get('gethistoriques/{id}/{selected}', 'EquipementController@gethistoriques');
    Route::get('getBatiments/{id}', 'EquipementController@getBatiments');
    Route::get('getBatiments/{id}/{selected}', 'EquipementController@getBatiments');
    Route::get('addBatiment/{id}','EquipementController@addBatiment');
    Route::post('saveBatiment','EquipementController@saveBatiment');
    Route::get('editBatiments/{id}', 'EquipementController@editBatiments');
    Route::get('afficheBatiment/{id}', 'EquipementController@afficheBatiment');
    Route::get('changeBatiments/{id}', 'EquipementController@changeBatiments');
    Route::post('updateBatiment','EquipementController@updateBatiment');
    Route::post('confirmeChangeBatiment','EquipementController@confirmeChangeBatiment');
    Route::get('DeleteBatiment/{id}','EquipementController@DeleteBatiment');
    Route::get('getPlans/{id}', 'EquipementController@getPlans');
    Route::get('getPlans/{id}/{selected}', 'EquipementController@getPlans');
    Route::get('addPlan/{id}','EquipementController@addPlan');
    Route::post('savePlan','EquipementController@savePlan');
    Route::get('EditPlans/{id}', 'EquipementController@EditPlans');
    Route::post('updatePlan','EquipementController@updatePlan');
    Route::get('DeletePlan/{id}','EquipementController@DeletePlan');
    Route::get('exportPDFFicheCommune/{equipement}/{employe}/{budget}/{contribuable}/{archive}/{actes}/{localites}/{annee}', 'EquipementController@exportPDFFicheCommune');
    Route::get('exportPDF/{type}/{secteur}/{localite}', 'EquipementController@exportPDF');
    Route::get('exportExcel/{type}/{secteur}/{localite}', 'EquipementController@exportExcel');
    Route::get('exportPDFBatiment/{id}', 'EquipementController@exportPDFBatiment');
    Route::get('exportequipementPDF/{id}', 'EquipementController@exportequipementPDF');
    Route::get('plans/get/{id}','PlanController@get');
    Route::get('plans/getItemes/{id}','PlanController@getItemes');
    Route::get('plans/getTab/{id}/{tab}','PlanController@getTab');
    Route::get('plans/addItem/{id}','PlanController@addItem');
    Route::post('plans/saveItem','PlanController@add');
    Route::get('plans/editItem/{id}', 'PlanController@editItem');
    Route::post('plans/updateItem','PlanController@updateItem');
    Route::get('plans/suiviItem/{id}','PlanController@suiviItem');
    Route::get('plans/getSuiviItemes/{id}','PlanController@getSuiviItemes');
    Route::get('plans/addSuivi/{id}','PlanController@addSuivi');
    Route::post('plans/saveSuiviItem','PlanController@saveSuiviItem');
    Route::get('plans/editSuivi/{id}', 'PlanController@editSuivi');
    Route::post('plans/updateSuivi','PlanController@updateSuivi');

});


Route::group(['prefix' => 'typesEquipements/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('', 'TypeEquipementController@index');
    Route::get('getDT', 'TypeEquipementController@getDT');
    Route::get('getDT/{id}', 'TypeEquipementController@getDT');
    Route::get('get/{id}','TypeEquipementController@get');
    Route::get('getTab/{id}/{tab}','TypeEquipementController@getTab');
    Route::get('add','TypeEquipementController@formAdd');
    Route::post('add','TypeEquipementController@add');
    Route::post('edit','TypeEquipementController@edit');
    Route::get('delete/{id}','TypeEquipementController@delete');
    Route::get('getElts/{id}', 'TypeEquipementController@getElts');
    Route::get('getElts/{id}/{selected}', 'TypeEquipementController@getElts');
    Route::get('EditElts/{id}', 'TypeEquipementController@EditElts');
    Route::post('updateElement','TypeEquipementController@updateElement');
    Route::get('addElement/{id}','TypeEquipementController@addElement');
    Route::get('deleteElement/{id}','TypeEquipementController@deleteElement');
    Route::post('saveElement','TypeEquipementController@saveElement');
    Route::get('showChoiceValue/{id}','TypeEquipementController@showChoiceValue');
    Route::post('editChoixElement','TypeEquipementController@editChoixElement');
});


Route::group(['prefix' => 'localites/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('', 'LocaliteController@index');
    Route::get('getDT', 'LocaliteController@getDT');
    Route::get('getDT/{id}', 'LocaliteController@getDT');
    Route::get('getCoordonnes/{id}', 'LocaliteController@getCoordonnes');
    Route::get('getCoordonnes/{id}/{selected}', 'LocaliteController@getCoordonnes');
    Route::get('get/{id}','LocaliteController@get');
    Route::get('getTab/{id}/{tab}','LocaliteController@getTab');
    Route::get('add','LocaliteController@formAdd');
    Route::get('ajoutCoordonnee/{id}','LocaliteController@ajoutCoordonnee');
    Route::post('add','LocaliteController@add');
    Route::post('edit','LocaliteController@edit');
    Route::get('editCoordonneeEd/{id}', 'LocaliteController@editCoordonneeEd');
    Route::post('editCoordonnee','LocaliteController@editCoordonnee');
    Route::post('addCoordonnee','LocaliteController@addCoordonnee');
    Route::get('delete/{id}','LocaliteController@delete');
    Route::get('deleteCoordonnees/{id}','LocaliteController@deleteCoordonnees');

});

Route::group(['prefix' => 'secteurs/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('', 'SecteurController@index');
    Route::get('getDT', 'SecteurController@getDT');
    Route::get('getDT/{id}', 'SecteurController@getDT');
    Route::get('get/{id}','SecteurController@get');
    Route::get('getTab/{id}/{tab}','SecteurController@getTab');
    Route::get('add','SecteurController@formAdd');
    Route::post('add','SecteurController@add');
    Route::post('edit','SecteurController@edit');
    Route::get('delete/{id}','SecteurController@delete');
});
Route::group(['prefix' => 'emplacements/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('', 'EmplacementController@index');
    Route::get('getDT', 'EmplacementController@getDT');
    Route::get('getDT/{id}', 'EmplacementController@getDT');
    Route::get('get/{id}','EmplacementController@get');
    Route::get('getTab/{id}/{tab}','EmplacementController@getTab');
    Route::get('add','EmplacementController@formAdd');
    Route::post('add','EmplacementController@add');
    Route::post('edit','EmplacementController@edit');
    Route::get('delete/{id}','EmplacementController@delete');
});

Route::group(['prefix' => 'tests/', 'middleware' => 'roles','roles' => [5]], function () {
    Route::get('testPdf', 'EquipementController@testPdf');
});
