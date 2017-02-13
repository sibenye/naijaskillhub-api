<?php
/**
 * Upload endpoint routes
 */
$app->post('/upload/profileImage',
        [
                'uses' => 'UploadController@uploadUserProfileImage',
                'middleware' => 'auth'
        ]);