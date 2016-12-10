<?php
/**
 * Upload endpoint routes
 */
$app->post('/upload/images', [
        'uses' => 'UploadController@uploadImage'
]);