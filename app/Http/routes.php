<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('layouts.app');
});

/*
ACARS ROUTES
*/
Route::group(['prefix' => 'acars', 'namespace' => 'ACARS'], function () {
	
	// XACARS
	Route::get('xacars/acars', 'XAcarsACARS@acars');
	Route::get('xacars/data', 'XAcarsACARS@flightdata');
});

/*
VAOS CORE API FILES
*/
Route::group(['prefix' => 'api', 'namespace' => 'API' /*, 'middleware' => 'api_auth' */], function () {

    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'schedule'], function() {
        	Route::get('/', 'ScheduleAPI@index');
            Route::post('/', 'ScheduleAPI@add');
            Route::post('/addjson', 'ScheduleAPI@jsonadd');
            Route::get('/search', 'ScheduleAPI@get');
        });
        Route::group(['prefix' => 'fleet'], function() {
        	Route::get('/', 'FleetAPI@index');
        	Route::post('/', 'FleetAPI@add');
        });
        Route::group(['prefix' => 'airport'], function() {
        	Route::get('/', 'AirportsAPI@index');
        	Route::get('/create', 'AirportsAPI@add');
        });
    });
});

// Dashboard Views
