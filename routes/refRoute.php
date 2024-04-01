<?php
Route::get('ref/getRefsDT/{n}/{selected}', 'refController@getRefsDT');
Route::get('ref/{n}', 'refController@listes');
Route::post('add_ref','refController@add_ref');
Route::get('ref/edit/{g}/{id}', 'refController@edit_ref');
Route::post('ref/update_ref',  'refController@update_ref');
Route::get('delete/{g}/{id}', 'refController@delete_ref');

Route::get('redirectto/{type}/{id}','refController@redirectTo');

