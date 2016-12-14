<?php
/**
 * @package App\Repositories.
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * CreditType Repository.
 * @author silver.ibenye
 *
 */
class CreditTypeRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\CreditType';
    }

    /**
     *
     * @param string $creditTypeName
     * @return Model
     */
    public function getCreditTypeByName($creditTypeName)
    {
        return $this->model->where('name', $creditTypeName)->first();
    }
}
