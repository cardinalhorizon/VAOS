<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::group(['prefix' => '1_0', 'namespace' => 'API'], function () {
    Route::post('/auth', 'AuthAPI@acarsLogin');
    Route::group(['prefix' => '/acars'], function ()
    {
        Route::post('/posrpt', 'AcarsAPI@position');
        Route::get('/wx', 'AcarsAPI@getwx');
    });
    // Airports Database Functions
    Route::group(['prefix' => '/airports'], function ()
    {
        Route::post('/', 'AirportsAPI@add');
    });
    // Schedule System
    Route::group(['prefix' => '/schedule'], function ()
    {
        Route::get('/', 'ScheduleAPI@index');
        Route::get('/bid', 'BidsAPI@getBid');
        Route::post('/bid', 'BidsAPI@fileBid');
        Route::post('/', 'ScheduleAPI@add');
    });
    Route::get('/bids', 'BidsAPI@getBid');
    Route::group(['prefix' => '/fleet'], function ()
    {
        Route::get('/', 'AircraftAPI@showAll');
        Route::post('/', 'AircraftAPI@addAircraft');
    });
    Route::post('/pireps', 'PIREPAPI@filePIREP');
    // ACARS Client and Live Server Information

    Route::get('/legacy', 'ScheduleAPI@legacytest');

});