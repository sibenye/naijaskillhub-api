<?php
/**
 * Users endpoint routes
 */
$app->get('/users/{id}/portfolios',
        [
                'uses' => 'UserPortfolioController@getAllUserPortfolios',
                'middleware' => 'acctType'
        ]);

$app->get('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@getUserImagesPortfolio',
                'middleware' => 'acctType'
        ]);

$app->get('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@getUserVideosPortfolio',
                'middleware' => 'acctType'
        ]);

$app->get('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@getUserAudiosPortfolio',
                'middleware' => 'acctType'
        ]);

$app->get('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@getUserCreditsPortfolio',
                'middleware' => 'acctType'
        ]);
$app->post('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@upsertUserImagePortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->post('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@upsertUserVideoPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->post('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@upsertUserAudioPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->post('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@upsertUserCreditPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->delete('/users/{id}/portfolios/images',
        [
                'uses' => 'UserPortfolioController@deleteUserImagePortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->delete('/users/{id}/portfolios/videos',
        [
                'uses' => 'UserPortfolioController@deleteUserVideoPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->delete('/users/{id}/portfolios/audios',
        [
                'uses' => 'UserPortfolioController@deleteUserAudioPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
$app->delete('/users/{id}/portfolios/credits',
        [
                'uses' => 'UserPortfolioController@deleteUserCreditPortfolio',
                'middleware' => [
                        'auth',
                        'acctType'
                ]
        ]);
