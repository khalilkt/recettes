<?php

Route::group(['prefix' => 'recettes/', 'middleware' => 'roles','roles' => [9]], function () {
    Route::get('', 'RecetteController@index');
    Route::get('getDT', 'RecetteController@getDT');
    Route::get('getDT/{id}', 'RecetteController@getDT');
    Route::get('get/{id}','RecetteController@get');
    Route::get('getTab/{id}/{tab}','RecetteController@getTab');
    Route::get('add','RecetteController@formAdd');

    Route::post('add','RecetteController@add');
    Route::post('edit','RecetteController@edit');
    Route::get('delete/{id}','RecetteController@delete');
});

Route::group(['prefix' => 'role_annees/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'RoleController@index');
    Route::get('getDT', 'RoleController@getDT');
    Route::get('getDT/{id}', 'RoleController@getDT');
    Route::get('get/{id}','RoleController@get');
    Route::get('getTab/{id}/{tab}','RoleController@getTab');
    Route::get('add','RoleController@formAdd');
    Route::post('add','RoleController@add');
    Route::post('edit','RoleController@edit');
    Route::get('delete/{id}','RoleController@delete');
});

Route::group(['prefix' => 'programmes/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'ProgrammesController@index');
    Route::get('getDT', 'ProgrammesController@getDT');
    Route::get('getDT/{id}', 'ProgrammesController@getDT');
    Route::get('get/{id}','ProgrammesController@get');
    Route::get('exportprogrammePDF/{id}','ProgrammesController@exportprogrammePDF');
    Route::get('getTab/{id}/{tab}','ProgrammesController@getTab');
    Route::get('add','ProgrammesController@formAdd');
    Route::post('add','ProgrammesController@add');
    Route::post('edit','ProgrammesController@edit');
    Route::get('delete/{id}','ProgrammesController@delete');
    Route::get('getDroitsDT/{id}','ProgrammesController@getDroitsDT');
    Route::get('updatedroits/{list}/{id}','ProgrammesController@updateGrouping');
});

Route::group(['prefix' => 'activites/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'ActiviteController@index');
    Route::get('getDT', 'ActiviteController@getDT');
    Route::get('getDT/{id}', 'ActiviteController@getDT');
    Route::get('get/{id}','ActiviteController@get');
    Route::get('getTab/{id}/{tab}','ActiviteController@getTab');
    Route::get('add','ActiviteController@formAdd');
    Route::post('add','ActiviteController@add');
    Route::post('edit','ActiviteController@edit');
    Route::get('delete/{id}','ActiviteController@delete');
});

Route::group(['prefix' => 'categories/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'CategorieController@index');
    Route::get('getDT', 'CategorieController@getDT');
    Route::get('getDT/{id}', 'CategorieController@getDT');
    Route::get('get/{id}','CategorieController@get');
    Route::get('getTab/{id}/{tab}','CategorieController@getTab');
    Route::get('add','CategorieController@formAdd');
    Route::post('add','CategorieController@add');
    Route::post('edit','CategorieController@edit');
    Route::get('delete/{id}','CategorieController@delete');
});

Route::group(['prefix' => 'forchets/', 'middleware' => 'roles','roles' => [1]], function () {
    Route::get('', 'ForchetController@index');
    Route::get('getDT', 'ForchetController@getDT');
    Route::get('getDT/{id}', 'ForchetController@getDT');
    Route::get('get/{id}','ForchetController@get');
    Route::get('getTab/{id}/{tab}','ForchetController@getTab');
    Route::get('add','ForchetController@formAdd');
    Route::post('add','ForchetController@add');
    Route::post('edit','ForchetController@edit');
    Route::get('delete/{id}','ForchetController@delete');
    Route::get('getCategorie/{id}','ForchetController@getCategorie');
});

Route::group(['prefix' => 'contribuables/', 'middleware' => 'roles','roles' => [9]], function () {
    Route::get('', 'ContribuableController@index');
    Route::get('getDT/{type}', 'ContribuableController@getDT');
    Route::get('getDT/{type}/{id}', 'ContribuableController@getDT');
    Route::get('get/{id}','ContribuableController@get');
    Route::get('getTab/{id}/{tab}','ContribuableController@getTab');
    Route::get('add','ContribuableController@formAdd');
    Route::post('add','ContribuableController@add');
    Route::post('edit','ContribuableController@edit');
    Route::get('delete/{id}','ContribuableController@delete');
    Route::get('montantArticleContribuable/{id}','ContribuableController@montantArticleContribuable');
    Route::post('openficherexcel','ContribuableController@openficherexcel');
    Route::get('getActvite/{activite}/{emplacement}/{taille}','ContribuableController@getActvite');
    Route::get('exportcontribuablePDF/{id}','ContribuableController@exportcontribuablePDF');
    Route::get('fermercontribuable/{id}','ContribuableController@fermercontribuable');
    Route::get('sutiationcontribuablePDF/{id}/{annee}','ContribuableController@sutiationcontribuablePDF');
    Route::get('fichdefermercontribuable/{id}','ContribuableController@fichdefermercontribuable');
    Route::get('exporterListeprotocolEch','ContribuableController@exporterListeprotocolEch');
    Route::get('getPayements/{id}','ContribuableController@getPayements');
    Route::get('getPayementmens1/{id}','ContribuableController@getPayementmens1');
    Route::get('selectionnerContribuable/{id}','ContribuableController@selectionnerContribuable');
    Route::get('getProtocoles/{id}','ContribuableController@getProtocoles');
    Route::get('visualiserproblem/{id}','ContribuableController@visualiserproblem');
    Route::get('suiviContibuable/{annee}','ContribuableController@suiviContibuable');
    Route::get('payercontibiable/{annee}/{id}','ContribuableController@payercontibiable');
    Route::get('suspension/{id}','ContribuableController@suspension');
    Route::get('playsup/{id}','ContribuableController@playsup');
    Route::get('changeAnnee/{annee}','ContribuableController@changeAnnee');
    Route::get('annulerPayement/{id}','ContribuableController@annulerPayement');
    Route::get('annulerProtocol/{id}','ContribuableController@annulerProtocol');
    Route::get('reprendrePayement/{id_mois}/{id_pay}','ContribuableController@reprendrePayement');
    Route::get('suspendrePayement/{id_mois}/{id_pay}','ContribuableController@suspendrePayement');
    Route::get('getPayementAnnne/{annee}','ContribuableController@getPayementAnnne');
    Route::get('recuperemontant/{id}','ContribuableController@recuperemontant');
    Route::get('recuperemontant1/{id}/{echance}','ContribuableController@recuperemontant1');
    Route::get('getPayementAnnne/{annee}/{contr}/{date1}/{date2}','ContribuableController@getPayementAnnne');
    Route::get('pdfSuiviPayementCtb/{annee}/{contr}/{date1}/{date2}/{role}','ContribuableController@pdfSuiviPayementCtb');
    Route::get('excelSuiviPayementCtb/{annee}/{contr}/{date1}/{date2}/{filtrage}','ContribuableController@excelSuiviPayementCtb');
    Route::get('newPayement/{id}','ContribuableController@newPayement');
    Route::get('newPayementPv/{id}','ContribuableController@newPayementPv');
    Route::get('annulerRole/{id}','ContribuableController@annulerRole');
    Route::get('newprotpcol/{id}','ContribuableController@newprotpcol');
    Route::get('getPayements/{id}/{selected}','ContribuableController@getPayements');
    Route::get('getPayementmens1/{id}/{selected}','ContribuableController@getPayementmens1');
    Route::post('savePayement','ContribuableController@savePayement');
    Route::post('savePayementpv','ContribuableController@savePayementpv');
    Route::post('saveProtocol','ContribuableController@saveProtocol');
    Route::post('saveSuspension','ContribuableController@saveSuspension');
});
