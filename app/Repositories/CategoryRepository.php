<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Category Repository.
 * @author silver.ibenye
 *
 */
class CategoryRepository extends BaseRepository
{

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::model()
     * @return string
     */
    public function model()
    {
        return 'App\Models\DAO\Category';
    }

    /**
     *
     * @param string $categoryName
     * @return Model
     */
    public function getCategoryByName($categoryName)
    {
        return $this->model->where('name', $categoryName)->first();
    }
}