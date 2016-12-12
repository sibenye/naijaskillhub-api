<?php
/**
 * @package App\Repositories
 */
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Base Repository class.
 * @author silver.ibenye
 *
 */
abstract class BaseRepository implements IBaseRepository
{
    /**
     *
     * @var Model
     */
    protected $model;

    /**
     * Base Repository constructor.
     */
    public function __construct()
    {
        $this->model = app()->make($this->model());
    }

    abstract public function model();

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::get()
     * @return Model|Collection
     * @throws ModelNotFoundException
     */
    public function get($id = NULL)
    {
        if ($id) {
            return $this->model->findOrFail($id);
        } else {
            return $this->model->all();
        }
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::create()
     * @return Model
     */
    public function create(array $model_attributes)
    {
        return $this->model->create($model_attributes);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::update()
     * @return Model
     */
    public function update($id, array $model_attributes)
    {
        return $this->model->where('id', $id)->update($model_attributes);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::delete()
     * @return void
     */
    public function delete($id)
    {
        $this->model->destroy($id);
    }
}
