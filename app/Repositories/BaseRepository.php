<?php

namespace App\Repositories;

abstract class BaseRepository implements IBaseRepository {
    protected $model;

    public function __construct() {
        $this->model = app()->make($this->model());
    }

    abstract public function model();

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::get()
     */
    public function get($id = NULL) {
        if ($id) {
            return $this->model->findOrFail($id);
        } else {
            return $this->model->all();
        }
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::create()
     */
    public function create(array $model_attributes) {
        return $this->model->create($model_attributes);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::update()
     */
    public function update($id, array $model_attributes) {
        return $this->model->where('id', $id)->update($model_attributes);
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\IBaseRepositories::delete()
     */
    public function delete($id) {
        return $this->model->destroy($id);
    }

}
