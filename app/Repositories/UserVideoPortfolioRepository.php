<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

/**
 * UserVideoPortfolio Repository.
 * @author silver.ibenye
 *
 */
class UserVideoPortfolioRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\VideoPortfolio';
    }
}
