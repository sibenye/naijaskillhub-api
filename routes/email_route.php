<?php
/**
 * Email send endpoint routes
 */
$app->group([
        'namespace' => 'Email',
        'prefix' => 'emails'
],
        function () use ($app) {
            $app->post('/transactionals/send',
                    [
                            'uses' => 'EmailController@sendTransactional'
                    ]);
        });