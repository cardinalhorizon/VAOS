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
Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {
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
        //Route::post('/', 'ScheduleAPI@add');
    });
    Route::get('/bids', 'BidsAPI@getBid');
    Route::post('/pireps', 'PIREPAPI@filePIREP');
});

/*
|--------------------------------------------------------------------------
| VAOS External ACARS Compatibility API
|--------------------------------------------------------------------------
|
| This section of the API is to primarily support ACARS clients not
| implementing the VAOS Central API Standard. Usually developers
| will include VAOS specific interface files which call these
| routes. For more information, please check the website.
|
*/

Route::group(['prefix' => 'acars', 'namespace' => 'LegacyACARS'], function () {

    // smartCARS 2
    Route::group(['prefix' => 'smartCARS'], function () {
        Route::post('/positionreport', 'smartCARS@positionreport');
        Route::post('/filepirep', 'smartCARS@filepirep');
        Route::get('/bids/{user_id}', 'smartCARS@getbids');
    });

    // XACARS
    Route::group(['prefix' => 'xacars'], function () {

    });
});