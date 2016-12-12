<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

/**
 * Base Repository Interface.
 * @author silver.ibenye
 *
 */
interface IBaseRepository
{

    /**
     * Find a record by Id or find all.
     *
     * @param integer $id
     */
    public function get($id = NULL);

    /**
     * Create a new record.
     *
     * @param array $attributes
     */
    public function create(array $model_attributes);

    /**
     * Update an existing record.
     *
     * @param integer $id
     * @param array $attributes
     */
    public function update($id, array $model_attributes);

    /**
     * Delete a record.
     *
     * @param integer $id
     */
    public function delete($id);
}

