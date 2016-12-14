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
class UserCreditPortfolioRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\CreditPortfolio';
    }

    public function getByUserIdAndCreditId($userId, $creditId)
    {
        return $this->model->where(
                [
                        [
                                'id',
                                '=',
                                $creditId
                        ],
                        [
                                'userId',
                                '=',
                                $userId
                        ]
                ])->get();
    }
}
