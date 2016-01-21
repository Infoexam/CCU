<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['middleware' => ['web']], function (Router $router) {
    $router->group(['middleware' => ['header']], function (Router $router) {
        $router->get('/', ['as' => 'home', 'uses' => 'HomeController@student']);
        $router->get('admin', ['as' => 'home.admin', 'uses' => 'HomeController@admin']);
        $router->get('exam', ['as' => 'home.exam', 'uses' => 'HomeController@exam']);
        $router->post('deploy', ['uses' => 'HomeController@deploy']);
    });

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
            $router->resource('lists.applies', 'ExamListApplyController', ['except' => ['create', 'edit', 'update']]);
            $router->resource('lists.results', 'ExamListResultController', ['only' => ['index', 'update']]);

            $router->get('configs', ['as' => 'api.v1.exam.configs.show', 'uses' => 'ExamConfigController@show']);
            $router->patch('configs', ['as' => 'api.v1.exam.configs.update', 'uses' => 'ExamConfigController@update']);
        });

        $router->get('users/search', ['as' => 'api.v1.users.search', 'uses' => 'UserController@search']);
        $router->resource('users', 'UserController', ['only' => ['store', 'show', 'update']]);

        $router->resource('announcements', 'AnnouncementController', ['except' => ['create', 'edit']]);
        $router->resource('faqs', 'FaqController', ['except' => ['create', 'edit']]);

        $router->group(['as' => 'api.v1.'], function (Router $router) {
            $router->get('ip-rules', ['as' => 'ip-rules.index', 'uses' => 'IpRuleController@index']);
            $router->post('ip-rules', ['as' => 'ip-rules.store', 'uses' => 'IpRuleController@store']);
            $router->delete('ip-rules', ['as' => 'ip-rules.destroy', 'uses' => 'IpRuleController@destroy']);

            $router->get('website-maintenance', ['as' => 'website-maintenance.show', 'uses' => 'WebsiteMaintenanceController@show@show']);
            $router->patch('website-maintenance', ['as' => 'website-maintenance.update', 'uses' => 'WebsiteMaintenanceController@update']);

            $router->delete('images', ['as' => 'image.destroy', 'uses' => 'ImageController@destroy']);
        });

        $router->group(['prefix' => 'categories'], function (Router $router) {
            $router->get('/', ['uses' => 'CategoryController@index']);
            $router->post('/', ['uses' => 'CategoryController@store']);
            $router->get('{categories}/{name?}', ['uses' => 'CategoryController@show']);
            $router->patch('{categories}/{name}', ['uses' => 'CategoryController@update']);
            $router->delete('{categories}/{name}', ['uses' => 'CategoryController@destroy']);
        });
    });
});
