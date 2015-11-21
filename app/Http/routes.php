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

$router->get('/', ['as' => 'home', 'uses' => 'HomeController@student']);
$router->get('admin', ['as' => 'home.admin', 'uses' => 'HomeController@admin']);
$router->get('exam', ['as' => 'home.exam', 'uses' => 'HomeController@exam']);

$router->group(['prefix' => 'api/v1', 'namespace' => 'Api\V1'], function (Router $router) {
    $router->group(['prefix' => 'auth', 'as' => 'api.v1.auth.'], function (Router $router) {
        $router->post('sign-in', ['as' => 'signIn', 'uses' => 'AuthController@signIn']);
        $router->get('sign-out', ['as' => 'signOut', 'uses' => 'AuthController@signOut']);
        $router->get('sso', ['as' => 'sso', 'uses' => 'AuthController@sso']);
    });

    $router->group(['prefix' => 'exam'], function (Router $router) {
        $router->get('sets/all-questions', ['as' => 'api.v1.exam.sets.allQuestions', 'uses' => 'ExamSetController@allQuestions']);
        $router->resource('sets', 'ExamSetController', ['except' => ['create', 'edit']]);
        $router->resource('sets.questions', 'ExamSetQuestionController', ['except' => ['create', 'edit']]);

        $router->resource('papers', 'ExamPaperController', ['except' => ['create', 'edit']]);
        $router->resource('papers.questions', 'ExamPaperQuestionController', ['except' => ['create', 'edit']]);

        $router->resource('lists', 'ExamListController', ['except' => ['create', 'edit']]);
        $router->resource('lists.applies', 'ExamListApplyController', ['except' => ['create', 'edit']]);

        $router->get('configs', ['as' => 'api.v1.exam.configs.show', 'uses' => 'ExamConfigController@show']);
        $router->patch('configs', ['as' => 'api.v1.exam.configs.update', 'uses' => 'ExamConfigController@update']);
    });

    $router->get('users/search', ['as' => 'api.v1.users.search', 'uses' => 'UserController@search']);
    $router->resource('users', 'UserController', ['only' => ['store', 'show', 'update']]);

    $router->resource('announcements', 'AnnouncementController', ['except' => ['create', 'edit']]);
    $router->resource('faqs', 'FaqController', ['except' => ['create', 'edit']]);
    $router->group(['prefix' => 'ip-rules'], function (Router $router) {
        $router->get('/', ['as' => 'api.v1.ip-rules.index', 'uses' => 'IpRuleController@index']);
        $router->post('/', ['as' => 'api.v1.ip-rules.store', 'uses' => 'IpRuleController@store']);
        $router->delete('/', ['as' => 'api.v1.ip-rules.destroy', 'uses' => 'IpRuleController@destroy']);
    });

    $router->resource('categories', 'CategoryController', ['except' => ['create', 'edit']]);
});
