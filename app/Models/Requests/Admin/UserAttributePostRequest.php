<?php
namespace App\Models\Requests\Admin;

use App\Models\Requests\IPostRequest;

class UserAttributePostRequest implements IPostRequest
{
    private $name;
    private $attributeId;
    private $attributeTypeId;
    private $displayName;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'name' => 'required'
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getUserAttributeId()
    {
        return $this->attributeId;
    }

    public function setUserAttributeId($userAttributeId)
    {
        $this->attributeId = $userAttributeId;
        return $this;
    }

    public function getUserAttributeTypeId()
    {
        return $this->attributeTypeId;
    }

    public function setUserAttributeTypeId($userAttributeTypeId)
    {
        $this->attributeTypeId = $userAttributeTypeId;
        return $this;
    }

    public function __construct($requestBody)
    {
        $this->attributeId = array_get($requestBody, 'attributeId', NULL);
        $this->attributeTypeId = array_get($requestBody, 'attributeTypeId', 1);
        $this->name = array_get($requestBody, 'name', NULL);
        $this->displayName = array_get($requestBody, 'displayName', NULL);
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        $attr = array ();
        $attr ['name'] = $this->name;
        if (!empty($this->attributeTypeId)) { // checking for both NULL and zero length string.
            $attr ['attributeTypeId'] = $this->attributeTypeId;
        }
        if (!empty($this->displayName)) { // checking for both NULL and zero length string.
            $attr ['displayName'] = $this->displayName;
        }

        return $attr;
    }
}
