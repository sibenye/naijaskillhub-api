<?php

namespace App\Repositories;

class UserAttributeRepository extends BaseRepository {

    public function model() {
        return 'App\Models\DAO\UserAttribute';
    }

    public function getUserAttributeByName($attributeName, $throw = false) {
        if ($throw) {
            return $this->model->where('name', $attributeName)->firstOrFail();
        } else {
            return $this->model->where('name', $attributeName)->first();
        }
    }

}
