<?php
/**
 * Users endpoint routes
 */
$app->get('/users/{id}', [
        'uses' => 'UserController@getUser'
]);

$app->get('/users/{id}/attributes',
        [
                'uses' => 'UserController@getUserAttributes'
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
                'uses' => 'UserController@upsertUserAttributeValue',
                'middleware' => 'auth'
        ]);

$app->post('/users/{id}/categories',
        [
                'uses' => 'UserController@linkUserToCategory',
                'middleware' => 'auth'
        ]);

$app->post('/users', [
        'uses' => 'UserController@registerUser'
]);

$app->post('/users/{id}/password/change',
        [
                'uses' => 'UserController@changeUserPassword',
                'middleware' => 'auth'
        ]);

$app->post('/users/{id}/password/reset',
        [
                'uses' => 'UserController@resetUserPassword'
        ]);

$app->post('/users/{id}/password/reset_request',
        [
                'uses' => 'UserController@resetRequest'
        ]);

$app->post('/users/{id}/activate',
        [
                'uses' => 'UserController@activateUser'
        ]);

$app->post('/users/{id}/emailAddress/change',
        [
                'uses' => 'UserController@changeUserEmailAddress',
                'middleware' => 'auth'
        ]);

$app->delete('/users/{id}/categories',
        [
                'uses' => 'UserController@unlinkUserFromCategory',
                'middleware' => 'auth'
        ]);
