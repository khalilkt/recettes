<?php
Route::get('couche/{id}','sigController@getLayout');
Route::get('coucheLocalite/{commune}','sigController@getLayoutLocalite');
Route::get('lead_data/{niveau}','sigController@read_data');

Route::get('baseLayers/{niveau}','sigController@baseLayers');
Route::get('groupedOverlays/{type}/{localite}','sigController@groupedOverlays');

Route::get('test_sig','sigController@test');
