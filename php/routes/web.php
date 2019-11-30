<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('auto-register-as-anonymous', 'AuthController@autoRegisterAsAnonymous');
    $router->post('register-as-anonymous', 'AuthController@registerAsAnonymous');
    $router->post('login-as-anonymous', 'AuthController@loginAsAnonymous');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');
    });
});
