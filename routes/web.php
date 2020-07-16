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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

if (! env('VAOS_INSTALLED', true)) {
    Route::get('/setup', 'Admin/InstallController@index');
    Route::get('/setup/integrity', 'Admin/InstallController@integrityCheck');
    Route::post('/setup', 'Admin/InstallController@install');
}
