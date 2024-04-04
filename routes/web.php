<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require_once 'refRoute.php';
require_once 'gedRoutes.php';
require_once 'patrimoineRoute.php';
require_once 'flRoute.php';
require_once 'employeRoute.php';
require_once 'recetteRoute.php';
require_once 'actesRoute.php';
require_once 'recettesRoute.php';
require_once 'archivesRoutes.php';
require_once 'courriersRoutes.php';
require_once 'sigRoute.php';
require_once 'partimoineCartoRoute.php';

Route::get('/', 'HomeController@dashboard');
Route::get('selectModule/{id}', 'HomeController@selectModule');
Route::get('home', 'HomeController@dashboard')->name('home');
Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

Auth::routes();

// exemple de routes pour familles
Route::group(['prefix' => 'familles/', 'middleware' => 'roles','roles' => [1]], function () {
	Route::get('', 'FamilleController@index');
	Route::get('getDT', 'FamilleController@getDT');
	Route::get('get/{id}','FamilleController@get');
	Route::get('getTab/{id}/{tab}','FamilleController@getTab');
	Route::get('add','FamilleController@formAdd');
	Route::post('add','FamilleController@add');
	Route::post('edit','FamilleController@edit');
	Route::get('delete/{id}','FamilleController@delete');
});
Route::get('/lang/{n}', function ($n) {
    Session::put('applocale', $n);
    return redirect('/');
});
