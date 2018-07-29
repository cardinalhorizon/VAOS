<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\MaterialCrew\Http\Controllers'], function()
{
    Route::group(['prefix' => '/flightops', 'middleware' => ['auth', 'App\Http\Middleware\ActiveAccountCheck'], 'as' => 'flightops.'], function() {
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
});

