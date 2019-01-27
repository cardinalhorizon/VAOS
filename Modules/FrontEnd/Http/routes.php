<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\FrontEnd\Http\Controllers'], function()
{
    Route::get('/', 'FrontEndController@index');
});
