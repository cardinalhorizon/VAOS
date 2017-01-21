<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


//
// Pilot Center

Route::group(['prefix' => '/flightops', 'namespace' => 'CrewOps', 'middleware' => 'auth'], function() {
    Route::get('/', 'CrewOpsController@index');
    Route::get('/profile', 'CrewOpsController@profile');
    Route::get('/profile/{id}', 'CrewOpsController@profileShow');
    Route::get('/profile/settings', 'CrewOpsController@profileEdit');
    Route::get('/schedule', 'CrewOpsController@getSchedule');
    Route::get('/schedule/search', 'CrewOpsController@getScheduleSearch');
    Route::get('/logbook', 'CrewOpsController@getLogbook');
    Route::resource('/bids', 'BiddingController');
});

// Web Admin Center
Route::group(['prefix' => '/admin', 'namespace' => 'Admin', 'middleware' => ['auth','App\Http\Middleware\AdminPerms']], function () {
    Route::get('/', 'AdminController@index');
    Route::resource('/schedule', 'ScheduleController');
    Route::resource('/fleet', 'FleetController');
    Route::resource('/bids', 'BidsController');
    Route::resource('/airlines', 'AirlineController');
    Route::resource('/airports', 'AirportController');
    Route::resource('/users', 'UsersController');
    Route::resource('/groups', 'UserGroupsController');
    Route::resource('/pireps', 'PIREPController');

    Route::group(['prefix' => '/data'], function () {
        Route::get('/system', 'ImportExportController@getSystem');
        Route::post('/system', 'ImportExportController@postSystem');
        Route::get('/airlines', 'ImportExportController@getAirlines');
        Route::post('/airlines', 'ImportExportController@postAirlines');
        Route::get('/fleet', 'ImportExportController@getFleet');
        Route::post('/fleet', 'ImportExportController@postFleet');
        Route::get('/schedule', 'ImportExportController@getSchedule');
        Route::post('/schedule', 'ImportExportController@postSchedule');
    });
});

// System Migration Routes
Route::get('/setup', 'Admin\InstallController@index');
Route::post('/install', 'Admin\InstallController@doInstall');
Route::get('/accountmigrate', 'Admin\InstallController@migrate');
