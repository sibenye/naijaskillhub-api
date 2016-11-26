<?php

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It is a breeze. Simply tell Lumen the URIs it should respond to
 * | and give it the Closure to call when that URI is requested.
 * |
 */
$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(
        [
                'namespace' => 'Admin',
                'prefix' => 'admin'
        ],
        function () use ($app) {
            // Using The "App\Http\Controllers\Admin" Namespace...

            $app->get('/categories',
                    [
                            'uses' => 'CategoryController@getCategories'
                    ]);

            $app->get('/categories/{id}',
                    [
                            'uses' => 'CategoryController@getCategory'
                    ]);

            $app->post('/categories',
                    [
                            'uses' => 'CategoryController@create'
                    ]);

            $app->put('/categories/{id}',
                    [
                            'uses' => 'CategoryController@update'
                    ]);

            $app->delete('/categories/{id}',
                    [
                            'uses' => 'CategoryController@delete'
                    ]);
        });
