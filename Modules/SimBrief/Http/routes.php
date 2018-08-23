<?php

Route::group(['middleware' => 'web', 'prefix' => 'simbrief', 'namespace' => 'Modules\SimBrief\Http\Controllers'], function () {
    Route::get('/', 'SimBriefController@index');
});
