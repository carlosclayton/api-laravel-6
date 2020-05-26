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
            $api->post('/register', [
                'uses' => 'RegisterController@store',
            ])->name('.register.store');
            $api->post('/password/forgot', [
                'uses' => 'ForgotPasswordController@sendResetLinkEmail',
            ])->name('.password.forgot');
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
            $api->post('/password/{user}', [
                'uses' => 'ForgotPasswordController@password'
            ])->name('.password');

        });

        $api->group([
            'namespace' => 'App\Http\Controllers',
            'middleware' => ['api.throttle', 'api.auth', 'auth:api'],
            'prefix' => 'users',
            'as' => '.users'
        ], function ($api) {
            $api->get('trashed', 'UsersController@trashed')->name('.trashed');
            $api->get('/', 'UsersController@index')->name('.index');
            $api->post('/', 'UsersController@store')->name('.store');
            $api->put('/{user}', 'UsersController@update')->name('.update');
            $api->get('/{user}', 'UsersController@show')->name('.show');
            $api->delete('/{user}', 'UsersController@destroy')->name('.destroy');
            $api->put('/restore/{user}', 'UsersController@restore')->name('.restore');

        });

        $api->group([
            'namespace' => 'App\Http\Controllers',
            'middleware' => ['api.throttle', 'api.auth', 'auth:api'],
            'prefix' => 'categories',
            'as' => 'api.categories',
        ], function ($api) {
            $api->get('/trashed', 'CategoriesController@trashed')->name('.trashed');
            $api->put('/restore/{category}', 'CategoriesController@restore')->name('.restore');
            $api->get('/', 'CategoriesController@index')->name('.index');
            $api->post('/', 'CategoriesController@store')->name('.store');
            $api->put('/{category}', 'CategoriesController@update')->name('.update');
            $api->get('/{category}', 'CategoriesController@show')->name('.show');
            $api->delete('/{user}', 'CategoriesController@destroy')->name('.destroy');
        });


        $api->group([
            'namespace' => 'App\Http\Controllers',
            'middleware' => ['api.throttle', 'api.auth', 'auth:api'],
            'prefix' => 'products',
            'as' => 'api.products',
        ], function ($api) {
            $api->get('/', 'ProductsController@index')->name('.index');
            $api->post('/', 'ProductsController@store')->name('.store');
            $api->put('/{product}', 'ProductsController@update')->name('.update');
        });

    });



