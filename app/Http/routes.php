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
        $api->resource('exams', 'ExamController', ['except' => ['create', 'edit'], 'parameters' => [
            'exams' => 'name',
            'questions' => 'uuid',
            'papers' => 'name',
        ]]);
        $api->resource('exams.images', 'ExamImageController', ['only' => ['index', 'store', 'destroy']]);
        $api->get('exams/{name}/questions/groups', 'ExamQuestionController@groups');
        $api->post('exams/{name}/questions/import', 'ExamQuestionController@import');
        $api->resource('exams.questions', 'ExamQuestionController');

        $api->resource('papers', 'PaperController', ['except' => ['create', 'edit']]);
        $api->get('papers/{name}/questions/all', 'PaperQuestionController@all');
        $api->get('papers/{name}/questions/show', 'PaperQuestionController@show');
        $api->resource('papers.questions', 'PaperQuestionController', ['only' => ['index', 'store']]);

        $api->get('categories/f/{category}/{name?}', 'CategoryController@filter');
        $api->get('revisions', 'RevisionController@index');
    });
});

$router->get('auth/old-website', 'Api\V1\AuthController@oldWebsite');
$router->get('auth/sso', 'Api\V1\AuthController@sso');

$router->post('deploy', 'HomeController@deploy');

$router->get('{redirect?}', ['as' => 'home', 'uses' => 'HomeController@home'])->where('redirect', '.*');
