<?php

namespace App\Repositories;

class UserAttributeRepository extends BaseRepository {

    public function model() {
        return 'App\Models\DAO\UserAttribute';
    }

    public function getUserAttributeByName($attributeName) {
        return $this->model->where('name', $attributeName)->first();
    }

}