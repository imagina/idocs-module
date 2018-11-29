<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/idocs'], function (Router $router) {
    $router->bind('category', function ($id) {
        return app('Modules\Idocs\Repositories\CategoryRepository')->find($id);
    });
    $router->get('categories', [
        'as' => 'admin.idocs.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'can:idocs.categories.index'
    ]);
    $router->get('categories/create', [
        'as' => 'admin.idocs.category.create',
        'uses' => 'CategoryController@create',
        'middleware' => 'can:idocs.categories.create'
    ]);
    $router->post('categories', [
        'as' => 'admin.idocs.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'can:idocs.categories.create'
    ]);
    $router->get('categories/{category}/edit', [
        'as' => 'admin.idocs.category.edit',
        'uses' => 'CategoryController@edit',
        'middleware' => 'can:idocs.categories.edit'
    ]);
    $router->put('categories/{category}', [
        'as' => 'admin.idocs.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'can:idocs.categories.edit'
    ]);
    $router->delete('categories/{category}', [
        'as' => 'admin.idocs.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'can:idocs.categories.destroy'
    ]);
    $router->bind('doc', function ($id) {
        return app('Modules\Idocs\Repositories\DocRepository')->find($id);
    });
    $router->get('docs', [
        'as' => 'admin.idocs.doc.index',
        'uses' => 'DocController@index',
        'middleware' => 'can:idocs.docs.index'
    ]);
    $router->get('docs/create', [
        'as' => 'admin.idocs.doc.create',
        'uses' => 'DocController@create',
        'middleware' => 'can:idocs.docs.create'
    ]);
    $router->post('docs', [
        'as' => 'admin.idocs.doc.store',
        'uses' => 'DocController@store',
        'middleware' => 'can:idocs.docs.create'
    ]);
    $router->get('docs/{doc}/edit', [
        'as' => 'admin.idocs.doc.edit',
        'uses' => 'DocController@edit',
        'middleware' => 'can:idocs.docs.edit'
    ]);
    $router->put('docs/{doc}', [
        'as' => 'admin.idocs.doc.update',
        'uses' => 'DocController@update',
        'middleware' => 'can:idocs.docs.edit'
    ]);
    $router->delete('docs/{doc}', [
        'as' => 'admin.idocs.doc.destroy',
        'uses' => 'DocController@destroy',
        'middleware' => 'can:idocs.docs.destroy'
    ]);
// append


});
