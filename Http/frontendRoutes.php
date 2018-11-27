<?php

use Illuminate\Routing\Router;
use Modules\Idocs\Entities\Category as Category;

/** @var Router $router */

        /** @var Router $router */
        $router->group(['prefix' => 'documents'], function (Router $router) {
            $locale = LaravelLocalization::setLocale() ?: App::getLocale();

            $router->get('/see_all', [
                'as' => $locale . 'documents.see_all',
                'uses' => 'PublicController@see_all',
                //'middleware' => config('asgard.idocs.config.middleware'),
            ]);
            
            $router->get('{category}', [
                'as' => $locale . 'documents.index',
                'uses' => 'PublicController@index',
                //'middleware' => config('asgard.idocs.config.middleware'),
            ]);
            
        });



