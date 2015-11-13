<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Routing\Router;

/** @var Router $router */

$router->get('/', ['as' => 'home', function () {
    return view('welcome');
}]);

$router->get('auth/sign-out', ['as' => 'auth.signOut', 'uses' => 'Api\V1\AuthController@signOut']);

$router->group(['prefix' => 'api/v1', 'namespace' => 'Api\V1'], function (Router $router) {
    $router->group(['prefix' => 'auth', 'as' => 'api.v1.auth.'], function (Router $router) {
        $router->post('sign-in', ['as' => 'signIn', 'uses' => 'AuthController@signIn']);
        $router->get('sso', ['as' => 'sso', 'uses' => 'AuthController@sso']);
    });

    $router->group(['prefix' => 'exam'], function (Router $router) {
        $router->resource('sets', 'ExamSetsController', ['except' => ['create']]);
    });
});
