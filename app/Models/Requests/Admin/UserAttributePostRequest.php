<?php

namespace App\Models\Requests\Admin;

use App\Models\Requests\IPostRequest;

class UserAttributePostRequest implements IPostRequest {
    private $name;
    private $userAttributeId;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules() {
        return [
                'name' => 'required'
        ];
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getUserAttributeId() {
        return $this->userAttributeId;
    }

    public function setUserAttributeId($userAttributeId) {
        $this->userAttributeId = $userAttributeId;
        return $this;
    }

    public function __construct($requestBody) {
        $this->userAttributeId = array_get($requestBody, 'userAttributeId',
                NULL);
        $this->name = array_get($requestBody, 'name', NULL);
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes() {
        $attr = array ();
        if (! empty($this->name)) { // checking for both NULL and zero length string.
            $attr ['name'] = $this->name;
        }

        return $attr;
    }

}