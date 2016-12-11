<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

class UserImagePortfolioRepository extends BaseRepository
{

    public function model()
    {
        return 'App\Models\DAO\ImagePortfolio';
    }
}