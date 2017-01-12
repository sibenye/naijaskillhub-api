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
$app->options('/', function () use ($app) {
    return $app->version();
});

$app->options('/{any}', function () use ($app) {
    return $app->version();
});

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/status', [
        'uses' => 'StatusController@status'
]);

$app->group([
        'namespace' => 'Admin',
        'prefix' => 'admin'
],
        function () use ($app) {
            require __DIR__ . '/admin/category_route.php';
            require __DIR__ . '/admin/userattribute_route.php';
        });

require __DIR__ . '/user_route.php';
require __DIR__ . '/user_portfolio_route.php';
require __DIR__ . '/upload_route.php';
require __DIR__ . '/email_route.php';
require __DIR__ . '/auth_route.php';
