<?php

/**
 * UserAttributes endpoint routes
 */
$app->get('/userAttributes',
        [
                'uses' => 'UserAttributeController@getUserAttributes'
        ]);

$app->get('/userAttributes/{id}',
        [
                'uses' => 'UserAttributeController@getUserAttribute'
        ]);

$app->post('/userAttributes',
        [
                'uses' => 'UserAttributeController@create'
        ]);

$app->put('/userAttributes/{id}',
        [
                'uses' => 'UserAttributeController@update'
        ]);