<?php

namespace App\Mappers;

use App\Models\Requests\UserAttributeValuePostRequest;

class UserAttributeValuePostRequestMapper implements IMapper {
    private $mapper;

    public function __construct(UserAttributeValueRequestMapper $mapper) {
        $this->mapper = $mapper;
    }

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @return UserAttributeValuePostRequest
     */
    public function map($in) {
        $out = new UserAttributeValuePostRequest();

        $arrayOfAttributeValueRequest = array ();

        foreach ($in ['attributes'] as $key => $value) {
            $arrayOfAttributeValueRequest [$key] = $this->mapper->map($value);
        }
        $out->setAttributes($arrayOfAttributeValueRequest);

        return $out;
    }

}
