<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

/**
 * UserImagePortfolio Repository.
 * @author silver.ibenye
 *
 */
class UserImagePortfolioRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\ImagePortfolio';
    }
}