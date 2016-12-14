<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

/**
 * UserVoiceClipPortfolio Repository.
 * @author silver.ibenye
 *
 */
class UserAudioPortfolioRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\AudioPortfolio';
    }
}
