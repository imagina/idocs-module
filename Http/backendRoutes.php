<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/idocs'], function (Router $router) {

    \CRUD::resource('idocs','category', 'CategoryController');
    \CRUD::resource('idocs','doc', 'DocController');
});

