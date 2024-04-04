<?php

// activités leaflet
Route::group(['middleware' => 'roles','roles' => [5]], function () {
    //Route::get('activites', 'mapController@index_activites');
    Route::get('carte', 'mapController@carte_commune');
    Route::get('/filterNiveau_activity/{n?}', 'mapController@filter_activites')->where('n', '(.*)');
    Route::get('/filterNiveau_patrimoin/{n?}', 'mapController@filter_patrimoin')->where('n', '(.*)');
    Route::get('/filterNiveau_marker/{n?}', 'mapController@filter_marker')->where('n', '(.*)');
    Route::get('/filterNiveau_emplacement/{n?}', 'mapController@filter_emplacement')->where('n', '(.*)');
    Route::get('/filterNiveau_polygon/{n?}', 'mapController@filter_polygon')->where('n', '(.*)');
//Route::get('plus_info_activites','mapController@plus_info_activites');
    Route::post('export_activite_excel', 'mapController@export_activite_excel');
    Route::get('getInfoActiviteSelected/{n}', 'mapController@info_activite');
    Route::get('coordonnee_gps_equipements/{type}/{localite}', 'mapController@coordonnee_gps_equipements');
    Route::get('coordonnee_gps_emplacement/{id}/{localite}', 'mapController@coordonnee_gps_emplacements');
    Route::get('getCarte/{type}', 'mapController@getCarte');
    Route::get('getCarteEmplacement', 'mapController@getCarteEmplacement');
    Route::get('getCarteLocalite/{id}', 'mapController@getCarteLocalite');
    Route::get('getInfoTypeEquipementSelected/{equipement}', 'mapController@info_equipement');
    Route::post('get_cordinate_polygon', 'mapController@get_cordinate_polygon');
    Route::get('getCordonateLocalite/{localite}', 'mapController@getCordonateLocalite');
    Route::get('delete_polygon_localite/{localite}', 'mapController@delete_polygon_localite');
    Route::get('/getInfoLocaliteSelected/{n}','mapController@info_localite');
    Route::get('plus_info_equipements','mapController@plus_info_equipements');

});
