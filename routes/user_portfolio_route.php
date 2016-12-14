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
                'uses' => 'UserPortfolioController@upsertUserImagePortfolio'
        ]);
$app->post('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@upsertUserVideoPortfolio'
        ]);
$app->post('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@upsertUserAudioPortfolio'
        ]);
$app->post('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@upsertUserCreditPortfolio'
        ]);
