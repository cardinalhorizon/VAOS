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

Route::get('/', function () {
    $settings = new \App\VASystem\DBSettings();

    return response()->json([
        'version'      => config('vaos.version'),
        'app_settings' => [
            'community_title' => $settings->get('community_title'),
            'colors'          => [
                'primary'   => $settings->get('app_color_primary'),
                'secondary' => $settings->get('app_color_secondary'),
                'accent'    => $settings->get('app_color_secondary'),
                'btn'       => $settings->get('app_color_btn'),
                'info'      => $settings->get('app_color_info'),
                'warning'   => $settings->get('app_color_warning'),
                'error'     => $settings->get('app_color_error'),
            ],
            'icon_url' => $settings->get('icon_url'),
            'logo_url' => $settings->get('logo_url'),
        ],
    ]);
});
