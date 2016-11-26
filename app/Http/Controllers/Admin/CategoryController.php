<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Models\Requests\CategoryPostRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    /**
     *
     * @var CategoryService
     */
    private $service;

    public function __construct(Request $request, CategoryService $service) {
        parent::__construct($request);
        $this->service = $service;
    }

    public function getCategories() {
        $categories = $this->service->get();

        return $this->response($categories);
    }

    public function getCategory($id) {
        $category = $this->service->get($id);

        return $this->response($category);
    }

    public function create() {
        $categoryPostRequest = new CategoryPostRequest($this->request->all());

        // validate request.
        $this->validateRequest($categoryPostRequest->getValidationRules());

        $category = $this->service->createCategory($categoryPostRequest);

        return $this->response($category);
    }

    public function update($id) {
        $categoryPostRequest = new CategoryPostRequest($this->request->all());

        $categoryPostRequest->setCategoryId($id);

        $this->service->updateCategory($categoryPostRequest);

        return $this->response();
    }

    public function delete($id) {
        $this->service->deleteCategory($id);

        return $this->response();
    }

}