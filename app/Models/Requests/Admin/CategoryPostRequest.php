<?php

namespace App\Models\Requests\Admin;

use App\Models\Requests\IPostRequest;

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
        $this->categoryId = array_get($requestBody, 'categoryId', NULL);
        $this->name = array_get($requestBody, 'name', NULL);
        $this->parentId = array_get($requestBody, 'parentId', NULL);
        $this->pluralName = array_get($requestBody, 'pluralName', NULL);
        $this->description = array_get($requestBody, 'description', NULL);
        $this->imageUrl = array_get($requestBody, 'imageUrl', NULL);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes() {
        $attr = array ();

        if (! empty($this->name)) { // checking for both NULL and zero length string.
            $attr ['name'] = $this->name;
        }
        if ($this->pluralName !== NULL) {
            $attr ['pluralName'] = $this->pluralName;
        }
        if ($this->parentId !== NULL) {
            $attr ['parentId'] = $this->parentId;
        }
        if ($this->description !== NULL) {
            $attr ['description'] = $this->description;
        }
        if ($this->imageUrl !== NULL) {
            $attr ['imageUrl'] = $this->imageUrl;
        }

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