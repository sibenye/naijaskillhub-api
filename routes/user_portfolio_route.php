<?php
/**
 * Users endpoint routes
 */
$app->get('/users/{id}/portfolios',
        [
                'uses' => 'UserPortfolioController@getAllUserPortfolios'
        ]);

$app->get('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@getUserImagesPortfolio'
        ]);

$app->get('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@getUserVideosPortfolio'
        ]);

$app->get('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@getUserAudiosPortfolio'
        ]);

$app->get('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@getUserCreditsPortfolio'
        ]);
$app->post('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@upsertUserImagePortfolio',
                'middleware' => 'auth'
        ]);
$app->post('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@upsertUserVideoPortfolio',
                'middleware' => 'auth'
        ]);
$app->post('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@upsertUserAudioPortfolio',
                'middleware' => 'auth'
        ]);
$app->post('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@upsertUserCreditPortfolio',
                'middleware' => 'auth'
        ]);
$app->delete('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@deleteUserImagePortfolio',
                'middleware' => 'auth'
        ]);
$app->delete('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@deleteUserVideoPortfolio',
                'middleware' => 'auth'
        ]);
$app->delete('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@deleteUserAudioPortfolio',
                'middleware' => 'auth'
        ]);
$app->delete('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@deleteUserCreditPortfolio',
                'middleware' => 'auth'
        ]);
