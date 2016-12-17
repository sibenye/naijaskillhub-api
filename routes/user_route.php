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
                'uses' => 'UserController@upsertUserAttributeValue'
        ]);

$app->post('/users/{id}/categories',
        [
                'uses' => 'UserController@linkUserToCategory'
        ]);

$app->post('/users', [
        'uses' => 'UserController@registerUser'
]);

$app->post('/users/{id}/password/change',
        [
                'uses' => 'UserController@changeUserPassword'
        ]);

$app->post('/users/{id}/password/reset',
        [
                'uses' => 'UserController@resetUserPassword'
        ]);

$app->post('/users/{id}/password/reset_request',
        [
                'uses' => 'UserController@resetRequest'
        ]);

$app->delete('/users/{id}/categories',
        [
                'uses' => 'UserController@unlinkUserFromCategory'
        ]);
