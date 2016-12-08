<?php

/**
 * Categories endpoint routes
 */
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
