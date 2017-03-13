<?php

use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Routing\Router as Router;

/* @var Router $router */
/* @var ApiRouter $api */

$api = app(ApiRouter::class);

$api->group(['version' => 'v1', 'middleware' => ['web'], 'namespace' => 'App\Http\Controllers\Api\V1'], function (ApiRouter $api) {
    $api->group(['prefix' => 'auth'], function (ApiRouter $api) {
        $api->post('sign-in', 'AuthController@signIn');
        $api->post('sign-out', 'AuthController@signOut');
    });

    $api->get('account/apply', 'AccountController@apply');

    $api->group(['middleware' => ['auth']], function (ApiRouter $api) {
        $api->group(['prefix' => 'account'], function (ApiRouter $api) {
            $api->get('profile', 'AccountController@profile');
            $api->get('applies', 'AccountController@applies');
        });

        $api->group(['prefix' => 'practice'], function (ApiRouter $api) {
            $api->get('exams', 'PracticeController@exam');
            $api->get('{exams}/processing', 'PracticeController@processing');
        });

        $api->resource('exams', 'ExamController', ['except' => ['create', 'edit'], 'parameters' => [
            'exams' => 'name',
            'questions' => 'uuid',
            'papers' => 'name',
            'listings' => 'code',
            'applies' => 'id',
            'gradings' => 'code',
            'tests' => 'code',
            'users' => 'username',
        ]]);
        $api->resource('exams.images', 'ExamImageController', ['only' => ['index', 'store', 'destroy']]);
        $api->get('exams/{name}/questions/groups', 'ExamQuestionController@groups');
        $api->post('exams/{name}/questions/import', 'ExamQuestionController@import');
        $api->resource('exams.questions', 'ExamQuestionController');

        $api->resource('papers', 'PaperController', ['except' => ['create', 'edit']]);
        $api->get('papers/{name}/questions/all', 'PaperQuestionController@all');
        $api->get('papers/{name}/questions/show', 'PaperQuestionController@show');
        $api->resource('papers.questions', 'PaperQuestionController', ['only' => ['index', 'store']]);

        $api->resource('listings', 'ListingController', ['except' => ['create', 'edit']]);
        $api->resource('listings.applies', 'ListingApplyController', ['except' => ['create', 'edit']]);

        $api->post('gradings/{code}/import', 'GradingController@import');
        $api->resource('gradings', 'GradingController', ['except' => ['create', 'edit']]);

        $api->get('categories/f/{category}/{name?}', 'CategoryController@filter');
        $api->get('revisions', 'RevisionController@index');

        $api->get('tests', 'TestController@index');
        $api->post('tests/{code}', 'TestController@store');
        $api->get('tests/{code}', 'TestController@show');
        $api->get('tests/{code}/timing', 'TestController@timing');
        $api->group(['prefix' => 'tests/{code}/manage'], function (ApiRouter $api) {
            $api->get('/', 'TestController@manage');
            $api->get('check-in', 'TestController@checkIn');
            $api->get('pc2', 'TestController@pc2');
            $api->patch('start', 'TestController@start');
            $api->patch('extend', 'TestController@extend');
            $api->patch('redo', 'TestController@redo');
        });

        $api->get('users/search', 'UserController@search');
        $api->resource('users', 'UserController');
    });
});

$router->get('auth/old-website', 'Api\V1\AuthController@oldWebsite');
$router->get('auth/sso', 'Api\V1\AuthController@sso');

$router->post('deploy', 'HomeController@deploy');

$router->get('{redirect?}', ['as' => 'home', 'uses' => 'HomeController@home'])->where('redirect', '.*');
