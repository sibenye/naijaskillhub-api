<?php

namespace App\Mappers;

use App\Models\Requests\UserAttributeValueRequest;

class UserAttributeValueRequestMapper implements IMapper {

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @return UserAttributeValueRequest
     */
    public function map($in) {
        $out = new UserAttributeValueRequest();

        $out->setAttributeId(array_get($in, 'attributeId', NULL));
        $out->setAttributeValue(array_get($in, 'attributeValue', NULL));

        return $out;
    }

}
