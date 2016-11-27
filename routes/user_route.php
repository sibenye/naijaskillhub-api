<?php
/**
 * Users endpoint routes
 */
$app->get('/users/{id}',
        [
                'uses' => 'UserController@getUser'
        ]);