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

Route::group(['prefix' => '/flightops', 'namespace' => 'CrewOps', 'middleware' => ['auth', 'App\Http\Middleware\ActiveAccountCheck'], 'as' => 'flightops.'], function() {
    Route::get('/', 'CrewOpsController@index')->name('index');
    Route::post('/settings', 'CrewOpsController@profileUpdate')->name('profile.update');
    Route::get('/settings', 'CrewOpsController@profileEdit')->name('profile.edit');
    Route::get('/profile/{id}', 'CrewOpsController@profileShow')->name('profile.view');
    Route::get('/schedule', 'CrewOpsController@getSchedule')->name('schedule');
    Route::get('/schedule/search', 'CrewOpsController@getScheduleSearch')->name('schedule.search');
    Route::get('/schedule/{id}/advbid', 'CrewOpsController@getScheduleAdvBid')->name('schedule.advbid');
    Route::get('/logbook', 'CrewOpsController@getLogbook')->name('logbook.view');
    Route::get('/logbook/{id}', 'CrewOpsController@getLogbookDetailed')->name('logbook.show');
    Route::resource('/flights', 'BiddingController');
    Route::get('/roster', 'CrewOpsController@getRoster')->name('roster');
    Route::post('/filepirep', 'CrewOpsController@postManualPirep')->name('filepirep');

    Route::group(['prefix' => '/airlines', 'as' => 'airlines.'], function () {
        Route::get('/', 'AirlineController@index')->name('index');
        Route::post('/', 'AirlineController@join')->name('join');
    });

    // Events System

    Route::group(['prefix' => '/events', 'as' => 'events.'], function () {
        Route::get('/', 'EventsController@index')->name('index');
        Route::get('/create', 'EventsController@create')->name('create');
        Route::get('/{slug}', 'EventsController@viewEvent')->name('view');
        Route::get('/{slug}/flights', 'EventsController@viewEventFlights')->name('view.flights');
        Route::post('/{slug}', 'EventsController@eventAction')->name('action');
    });
});

Route::group(['prefix' => '/staff', 'namespace' => 'AirlineStaff', 'middleware' => ['auth', 'App\Http\Middleware\AirlineStaffCheck'], 'as' => 'staff.'], function () {
    Route::group(['prefix' => '{airline}'], function () {
        Route::resource('/schedule', 'ScheduleController');
        Route::resource('/fleet', 'FleetController');
        Route::resource('/flights', 'BidsController');
        Route::resource('/users', 'UsersController');
        Route::resource('/logbook', 'PIREPController');
    });
});
// System Admin
Route::group(['prefix' => '/admin', 'namespace' => 'Admin', 'middleware' => ['auth','App\Http\Middleware\AdminPerms'], 'as' => 'admin.'], function () {
    Route::get('/', 'AdminController@index')->name('index');
    Route::resource('/schedule', 'ScheduleController');
    Route::resource('/fleet', 'FleetController');
    Route::resource('/flights', 'BidsController');
    Route::resource('/airlines', 'AirlineController');
    Route::resource('/airports', 'AirportController');
    Route::resource('/users', 'UsersController');
    Route::post('/users/{id}/airlinemod', 'UsersController@airlinemod')->name('users.airlinemod');
    Route::resource('/typeratings', 'TypeRatingsController');
    Route::resource('/pireps', 'PIREPController');
    Route::get('/migrations', 'InstallController@viewMigrations')->name('migrations.index');
    Route::get('/migrate', 'InstallController@dbMigrate')->name('migrations.migrate');
    Route::group(['prefix' => '/data', 'as' => 'data.'], function () {
        Route::get('/system', 'ImportExportController@getSystem');
        Route::post('/system', 'ImportExportController@postSystem');
        Route::get('/airlines', 'ImportExportController@getAirlines');
        Route::post('/airlines', 'ImportExportController@postAirlines');
        Route::get('/fleet', 'ImportExportController@getFleet')->name('fleet');
        Route::post('/fleet', 'ImportExportController@postFleet');
        Route::get('/schedule', 'ImportExportController@getSchedule');
        Route::post('/schedule', 'ImportExportController@postSchedule');
    });
});
Route::get('/vatsim/update', 'OnlineData\VatsimData@updateAll');
// System Migration Routes
Route::get('/setup', 'Admin\InstallController@index');
Route::post('/settings', 'Admin\InstallController@settings');
Route::post('/install', 'Admin\InstallController@doInstall');
Route::get('/setup/import', function () {
    return view('install.import');
});
Route::get('/accountmigrate', 'Admin\InstallController@migrate');

