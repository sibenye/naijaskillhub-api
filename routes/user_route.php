<?php
/**
 * Users endpoint routes
 */
$app->get('/users/{id}',
        [
                'uses' => 'UserController@getUser'
        ]);

$app->get('/users/{id}/attributes',
        [
                'uses' => 'UserController@getUserAttributes'
        ]);

$app->get('/users/{id}/portfolios',
        [
                'uses' => 'UserController@getUserPortfolios'
        ]);

$app->get('/users/{id}/categories',
        [
                'uses' => 'UserController@getUserCategories'
        ]);

$app->get('/users/{id}/credentialTypes',
        [
                'uses' => 'UserController@getUserCredentialTypes'
        ]);