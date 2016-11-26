<?php

namespace App\Models\Requests;

class CategoryPostRequest {
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

    public function __construct($uri_request_body) {
        $this->categoryId = array_get($uri_request_body, 'categoryId', null);
        $this->name = array_get($uri_request_body, 'name', null);
        $this->pluralName = array_get($uri_request_body, 'pluralName', null);
        $this->description = array_get($uri_request_body, 'description', null);
        $this->imageUrl = array_get($uri_request_body, 'imageUrl', null);
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

}