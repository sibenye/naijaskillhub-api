<?php

/**
 * UserAttributeTypes endpoint routes
 */
$app->get('/userAttributeTypes',
        [
                'uses' => 'UserAttributeTypeController@getUserAttributeTypes'
        ]);

$app->get('/userAttributeTypes/{id}',
        [
                'uses' => 'UserAttributeTypeController@getUserAttributeType'
        ]);

