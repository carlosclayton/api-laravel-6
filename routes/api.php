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


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'middleware' => 'api.throttle',
    'limit' => 50,
    'expires' => 1
    ],
    function ($api) {
    $api->group([
        'namespace' => 'App\Http\Controllers\Auth'
    ], function ($api) {
        $api->post('/access_token', 'LoginController@accessToken')->name('.access_token');
    });

    $api->group([
        'namespace' => 'App\Http\Controllers\Auth',
        'middleware' => ['api.auth', 'auth:api'],
    ], function ($api) {
        $api->get('/user', function (Request $request) {
            return response([
                'data' => $request->user()
            ]);
        })->name('.user');

        $api->post('/logout', [
            'uses' => 'LoginController@logout',
        ])->name('.logout');

        $api->post('/refresh_token', [
            'uses' => 'LoginController@refreshToken'
        ])->name('.refresh_token');
    });
});


