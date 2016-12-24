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

$app->post('/users/{id}/password/change',
        [
                'uses' => 'UserController@changeUserPassword',
                'middleware' => 'auth'
        ]);

$app->post('/users/password/reset',
        [
                'uses' => 'UserController@resetUserPassword'
        ]);

$app->post('/users/password/forgot',
        [
                'uses' => 'UserController@forgotPassword'
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

$app->post('/users/{id}/addStandardCredential',
        [
                'uses' => 'UserController@addStandardCredential',
                'middleware' => 'auth'
        ]);

$app->post('/users/addSocialCredential',
        [
                'uses' => 'UserController@addSocialCredential'
        ]);

$app->delete('/users/{id}/categories',
        [
                'uses' => 'UserController@unlinkUserFromCategory',
                'middleware' => 'auth'
        ]);
