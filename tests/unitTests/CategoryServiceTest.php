<?php

namespace Tests\unitTests;

use App\Services\CategoryService;
use App\Repositories\CategoryRepository;
use App\Models\Requests\Admin\CategoryPostRequest;

class CategoryServiceTest extends \TestCase {
    private $categoryService;
    private $categoryRepositoryMock;
    private $categoryPostRequest;

    public function setUp() {
        parent::setUp();

        $this->categoryRepositoryMock = $this->createMock(
                CategoryRepository::class);

        $this->categoryService = new CategoryService(
            $this->categoryRepositoryMock);
    }

    public function testCreateCategory() {
        $this->categoryPostRequest = $this->createCategoryPostRequest();
        $this->categoryRepositoryMock->expects($this->once())->method(
                'getCategoryByName')->with($this->categoryPostRequest->getName());

        $this->categoryRepositoryMock->expects($this->once())->method('get')->with(
                $this->categoryPostRequest->getParentId());

        $modelAttributes = $this->categoryPostRequest->buildModelAttributes();
        $this->categoryRepositoryMock->expects($this->once())->method('create')->with(
                $modelAttributes);

        $this->categoryService->createCategory($this->categoryPostRequest);
    }

    public function testCreateCategoryWithoutParentId() {
        $this->categoryPostRequest = $this->createCategoryPostRequest();
        $this->categoryPostRequest->setParentId(NULL);
        $this->categoryRepositoryMock->expects($this->once())->method(
                'getCategoryByName')->with($this->categoryPostRequest->getName());

        $this->categoryRepositoryMock->expects($this->never())->method('get')->with(
                $this->categoryPostRequest->getParentId());

        $modelAttributes = $this->categoryPostRequest->buildModelAttributes();
        $this->categoryRepositoryMock->expects($this->once())->method('create')->with(
                $modelAttributes);

        $this->categoryService->createCategory($this->categoryPostRequest);
    }

    public function testUpdateCategory() {
        $this->categoryPostRequest = $this->createCategoryPostRequest();
        $this->categoryPostRequest->setCategoryId(2);
        $this->categoryRepositoryMock->expects($this->once())->method(
                'getCategoryByName')->with($this->categoryPostRequest->getName());

        $this->categoryRepositoryMock->expects($this->at(2))->method('get');

        $modelAttributes = $this->categoryPostRequest->buildModelAttributes();
        $this->categoryRepositoryMock->expects($this->once())->method('update')->with(
                $this->categoryPostRequest->getCategoryId(), $modelAttributes);

        $this->categoryService->updateCategory($this->categoryPostRequest);
    }

    private function createCategoryPostRequest() {
        return new CategoryPostRequest(
            [
                    'name' => 'testCat',
                    'parentId' => 1
            ]);
    }

}