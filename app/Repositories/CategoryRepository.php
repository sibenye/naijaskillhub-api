<?php

namespace App\Repositories;

class CategoryRepository extends BaseRepository {

    public function model() {
        return 'App\Models\DAO\Category';
    }

    public function getCategoryByName($categoryName) {
        return $this->model->where('name', $categoryName)->firstOrFail();
    }

}