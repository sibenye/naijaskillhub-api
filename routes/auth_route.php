<?php
/**
 * Authentication endpoint routes
 */
$app->post('/login', [
        'uses' => 'AuthController@login'
]);

$app->post('/logout', [
        'uses' => 'AuthController@logout'
]);

$app->post('/register', [
        'uses' => 'AuthController@register'
]);

