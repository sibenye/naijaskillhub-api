<?php

namespace App\Models\Requests;

class CategoryPostRequest implements IPostRequest {
    private $categoryId;
    private $name;
    private $pluralName;
    private $parentId;
    private $description;
    private $imageUrl;

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getPluralName() {
        return $this->pluralName;
    }

    public function setPluralName($pluralName) {
        $this->pluralName = $pluralName;
        return $this;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function __construct($requestBody) {
        $this->categoryId = array_get($requestBody, 'categoryId', null);
        $this->name = array_get($requestBody, 'name', null);
        $this->parentId = array_get($requestBody, 'parentId', null);
        $this->pluralName = array_get($requestBody, 'pluralName', null);
        $this->description = array_get($requestBody, 'description', null);
        $this->imageUrl = array_get($requestBody, 'imageUrl', null);
    }

    public function buildModelAttributes() {
        $attr = array ();

        $attr ['name'] = $this->name;
        $attr ['pluralName'] = $this->pluralName;
        $attr ['parentId'] = $this->parentId;
        $attr ['description'] = $this->description;
        $attr ['imageUrl'] = $this->imageUrl;

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::validationRules()
     */
    public function getValidationRules() {
        return [
                'name' => 'required'
        ];
    }

}