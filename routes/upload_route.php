<?php
/**
 * Upload endpoint routes
 */
$app->post('/upload/profileImage',
        [
                'uses' => 'UploadController@uploadUserProfileImage',
                'middleware' => 'auth'
        ]);

$app->post('/upload/portfolio/image',
        [
                'uses' => 'UploadController@uploadUserPortfolioImage',
                'middleware' => 'auth'
        ]);

$app->post('/upload/portfolio/audio',
        [
                'uses' => 'UploadController@uploadUserPortfolioAudio',
                'middleware' => 'auth'
        ]);

$app->post('/upload/validateFile',
        [
                'uses' => 'UploadController@validateFileUpload'
        ]);
