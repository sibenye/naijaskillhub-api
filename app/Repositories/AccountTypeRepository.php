<?php
/**
 * @package App\Repositories.
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * AccountType Repository.
 * @author silver.ibenye
 *
 */
class AccountTypeRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\AccountType';
    }

    /**
     *
     * @param string $accountTypeName
     * @return Model
     */
    public function getAccountTypeByName($accountTypeName)
    {
        return $this->model->where('name', $accountTypeName)->first();
    }
}
