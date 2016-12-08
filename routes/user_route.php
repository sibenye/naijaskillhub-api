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
                'uses' => 'UserController@getAllUserPortfolios'
        ]);

$app->get('/users/{id}/portfolios/images',
        [
                'uses' => 'UserController@getUserImagesPortfolio'
        ]);

$app->get('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserController@getUserVideosPortfolio'
        ]);

$app->get('/users/{id}/portfolios/voiceclips',
        [
                'uses' => 'UserController@getUserVoiceclipsPortfolio'
        ]);

$app->get('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserController@getUserCreditsPortfolio'
        ]);

$app->get('/users/{id}/categories',
        [
                'uses' => 'UserController@getUserCategories'
        ]);

$app->get('/users/{id}/credentialTypes',
        [
                'uses' => 'UserController@getUserCredentialTypes'
        ]);

$app->post('/users/{id}/attributes',
        [
                'uses' => 'UserController@upsertUserAttributeValue'
        ]);

$app->post('/users/{id}/categories',
        [
                'uses' => 'UserController@linkUserToCategory'
        ]);

$app->post('/users',
        [
                'uses' => 'UserController@registerUser'
        ]);

$app->delete('/users/{id}/categories',
        [
                'uses' => 'UserController@unlinkUserFromCategory'
        ]);
