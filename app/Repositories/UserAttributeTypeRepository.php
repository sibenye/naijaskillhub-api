<?php
/**
 * @package App\Repositories.
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * UserAttributeType Repository.
 * @author silver.ibenye
 *
 */
class UserAttributeTypeRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\UserAttributeType';
    }

    /**
     *
     * @param string $attributeTypeName
     * @return Model
     */
    public function getUserAttributeTypeByName($attributeTypeName, $throw = false)
    {
        if ($throw) {
            return $this->model->where('name', $attributeTypeName)->firstOrFail();
        } else {
            return $this->model->where('name', $attributeTypeName)->first();
        }
    }
}
