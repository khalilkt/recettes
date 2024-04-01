<?php

Route::group(['prefix' => 'finances/', 'middleware' => 'roles','roles' => [4]], function () {
    Route::get('', 'FinanceLocaleController@index');
    Route::get('getDT', 'FinanceLocaleController@getDT');
    Route::get('getDT/{id}', 'FinanceLocaleController@getDT');
    Route::get('get/{id}','FinanceLocaleController@get');
    Route::get('getTab/{id}/{tab}','FinanceLocaleController@getTab');
    Route::get('ecritures/get/{id}','FinanceLocaleController@getEcriture');
    Route::get('ecritures/getTab/{id}/{tab}','FinanceLocaleController@getTabEcriture');
    Route::get('editEcritures/get/{id}','FinanceLocaleController@getEditEcriture');
    Route::get('editEcritures/getTab/{id}/{tab}','FinanceLocaleController@getTabEditEcriture');
    Route::get('add','FinanceLocaleController@formAdd');
    Route::post('add','FinanceLocaleController@add');
    Route::post('edit','FinanceLocaleController@edit');
    Route::post('updateEcriture','FinanceLocaleController@updateEcriture');
    Route::get('delete/{id}','FinanceLocaleController@delete');
    Route::get('deleteEcriture/{id}/{sens}','FinanceLocaleController@deleteEcriture');
    Route::get('valideBg/{id}','FinanceLocaleController@valideBg');
    Route::get('devalideBg/{id}','FinanceLocaleController@devalideBg');
    Route::get('clotureBg/{id}','FinanceLocaleController@clotureBg');
    Route::get('getbudget/{id}','FinanceLocaleController@getbudget');
    Route::get('situationBudget/{id}','FinanceLocaleController@situationBudget');
    Route::get('suiviExecution/{id}','FinanceLocaleController@suiviExecution');
    Route::get('EditEcriture/{id}/{sen}','FinanceLocaleController@EditEcriture');
    Route::get('historiqueEcriture/{id}','FinanceLocaleController@historiqueEcriture');
    Route::get('getEcritures/{id}','FinanceLocaleController@getEcritures');
    Route::get('getEcritures/{id}/{selected}','FinanceLocaleController@getEcritures');
    Route::get('exportPDFBudgetFiltre/{id}/{niveau}/{classe}','FinanceLocaleController@exportPDFBudgetFiltre');
    Route::get('exportEXCELBudgetFiltre/{id}/{niveau}/{classe}','FinanceLocaleController@exportEXCELBudgetFiltre');
    Route::get('exporterSuiviExecution/{id}/{date1}/{date2}/{sence}/{texte}/{detail}/{niveau}','FinanceLocaleController@exporterSuiviExecution');
    Route::get('exporterSuiviExecutionExcel/{id}/{date1}/{date2}/{sence}/{texte}/{detail}/{niveau}','FinanceLocaleController@exporterSuiviExecutionExcel');
    Route::get('exporterSituationBudgetaire/{id}/{date1}/{date2}/{type}/{detail}','FinanceLocaleController@exporterSituationBudgetaire');
    Route::get('exporterSituationBudgetaireExcel/{id}/{date1}/{date2}/{type}/{detail}','FinanceLocaleController@exporterSituationBudgetaireExcel');
    Route::get('BudgetFiltre/{id}/{niveau}/{classe}','FinanceLocaleController@BudgetFiltre');
    Route::get('visualiserBudget/{id}','FinanceLocaleController@visualiserBudget');
    Route::get('openOldbudget/{annee}','FinanceLocaleController@openOldbudget');
    Route::post('editbudget','FinanceLocaleController@editbudget');
    Route::post('saveEcriture','FinanceLocaleController@saveEcriture');
    Route::get('exportPDFBudget/{id}','FinanceLocaleController@exportPDFBudget');
    Route::get('testpdf','FinanceLocaleController@testpdf');

});
