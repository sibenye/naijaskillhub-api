<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * UserAttribute Repository.
 * @author silver.ibenye
 *
 */
class UserAttributeRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\UserAttribute';
    }

    /**
     *
     * @param string $attributeName
     * @param boolean $throw
     * @return Model
     */
    public function getUserAttributeByName($attributeName, $throw = false)
    {
        if ($throw) {
            return $this->model->where('name', $attributeName)->firstOrFail();
        } else {
            return $this->model->where('name', $attributeName)->first();
        }
    }

    /**
     *
     * @param integer $attributeTypeId
     * @return Collection
     */
    public function getUserAttributesByType($attributeTypeId)
    {
        return $this->model->where('attributeTypeId', $attributeTypeId)->get();
    }
}
