<?php

use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Routing\Router as Router;

/* @var Router $router */
/* @var ApiRouter $api */

$api = app(ApiRouter::class);

$api->version('v1', function (ApiRouter $api) {
    //
});

$router->get('{redirect}', ['as' => 'home', 'uses' => 'HomeController@home'])->where('redirect', '.*');
