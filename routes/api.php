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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'admin', 'name' => 'admin.', 'namespace' => 'AdminAPI', 'middleware' => 'auth:api'], function() {
    // Route::resource('aircraft', 'AircraftAPIController');
    // Route::resource('schedule', 'ScheduleAPIController');
    // Route::resource('type_ratings', 'TypeRatingsAPIController');
    // Route::resource('trips', 'TripsAPIController');
    // Route::resource('users', 'UsersAPIController');
    // Route::resource('flights', 'FlightsAPIController');
});
