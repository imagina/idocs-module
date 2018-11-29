<?php

use Illuminate\Routing\Router;
/** @var Router $router */


$locale = LaravelLocalization::setLocale() ?: App::getLocale();

$router->group(['prefix' =>'/document'], function (Router $router)use($locale) {

    $router->get('/', [
        'as' => $locale.'.idocs.index',
        'uses' => 'PublicController@index',
    ]);
    $router->get('{category}', [
        'as' => $locale.'.idocs.category',
        'uses' => 'PublicController@category',

    ]);
    $router->get('{category}/{doc}', [
        'as' => $locale.'.idocs.show',
        'uses' => 'PublicController@show',

    ]);
    $router->get('{user_id}', [
        'as' => $locale.'.idocs.user',
        'uses' => 'PublicController@indexUser',
        'middleware' => 'can:idocs.categories.create'
    ]);

});
