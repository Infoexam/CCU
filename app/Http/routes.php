<?php

use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Routing\Router as Router;

/* @var Router $router */
/* @var ApiRouter $api */

$api = app(ApiRouter::class);

$api->group(['version' => 'v1', 'middleware' => ['web'], 'namespace' => 'App\Http\Controllers\Api\V1'], function (ApiRouter $api) {
    $api->get('account/profile', 'AccountController@profile');

    $api->group(['prefix' => 'auth'], function (ApiRouter $api) {
        $api->post('sign-in', 'AuthController@signIn');
        $api->get('sign-out', 'AuthController@signOut');
    });

    $api->group(['middleware' => 'auth'], function (ApiRouter $api) {
        $api->group(['prefix' => 'practice'], function (ApiRouter $api) {
            $api->get('exams', 'PracticeController@exam');
            $api->get('{exams}/processing', 'PracticeController@processing');
        });
    });

    $api->group(['middleware' => 'auth:admin'], function (ApiRouter $api) {
        $api->get('exams/{exams}/images', 'ExamController@image');
        $api->post('exams/{exams}/images', 'ExamController@storeImage');
        $api->resource('exams', 'ExamController', ['except' => ['create', 'edit']]);
        $api->get('exams/{exams}/questions/groups', 'ExamQuestionController@groups');
        $api->resource('exams.questions', 'ExamQuestionController');

        $api->get('categories/f/{category}/{name?}', 'CategoryController@filter');
        $api->resource('categories', 'CategoryController', ['except' => ['create', 'show', 'edit']]);
    });
});

$router->get('{redirect}', ['middleware' => ['web'], 'as' => 'home', 'uses' => 'HomeController@home'])->where('redirect', '.*');
