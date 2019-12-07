<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');
        $router->post('edit-name', 'ProfileController@editName');
        $router->post('edit-email', 'ProfileController@editEmail');
        $router->post('edit-password', 'ProfileController@editPassword');
        $router->post('get-data', 'ProfileController@getData');
    });
});
