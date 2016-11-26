<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Requests\CategoryPostRequest;

class CategoryService {

    /**
     *
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(CategoryRepository $repository) {
        $this->repository = $repository;
    }

    public function get($id = NULL) {
        return $this->repository->get($id);
    }

    public function createCategory(CategoryPostRequest $request) {
        $modelAttributes = $request->buildModelAttributes();

        $category = $this->repository->create($modelAttributes);

        return $category;
    }

    public function updateCategory(CategoryPostRequest $request) {
        $modelAttributes = $request->buildModelAttributes();

        $category = $this->repository->update($request->getCategoryId(),
                $modelAttributes);

        return $category;
    }

    public function deleteCategory($id) {
        $this->repository->delete($id);
    }

}