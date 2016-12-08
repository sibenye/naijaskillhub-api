<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Requests\Admin\CategoryPostRequest;
use Illuminate\Validation\ValidationException;

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
        // ensure that the category name is not taken
        if ($this->repository->getCategoryByName($request->getName())) {
            throw new ValidationException(NULL,
                'The Category name is already in use.');
        }

        if ($request->getParentId()) {
            // confirm that the parentId exists
            $this->repository->get($request->getParentId());
        }

        $modelAttributes = $request->buildModelAttributes();

        $category = $this->repository->create($modelAttributes);

        return $category;
    }

    public function updateCategory(CategoryPostRequest $request) {
        // ensure the category exists
        $this->repository->get($request->getCategoryId());

        if ($request->getName()) {
            // ensure that the category name is not taken
            $existingCategoryByName = $this->repository->getCategoryByName(
                    $request->getName());

            if ($existingCategoryByName &&
                     $existingCategoryByName ['id'] != $request->getCategoryId()) {
                throw new ValidationException(NULL,
                    'The Category name is already in use.');
            }
        }

        if ($request->getParentId()) {
            // confirm that the parentId exists
            $this->repository->get($request->getParentId());
        }

        $modelAttributes = $request->buildModelAttributes();

        $category = $this->repository->update($request->getCategoryId(),
                $modelAttributes);

        return $category;
    }

    public function deleteCategory($id) {
        $this->repository->delete($id);
    }

}
