<?php

/** @var Router $router */

use Laravel\Lumen\Routing\Router;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('reset-password-confirmation', 'AuthController@resetPasswordConfirmation');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('reset-password', 'AuthController@resetPassword');
        $router->post('reset-password', 'AuthController@logout');
    });
});
