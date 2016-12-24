<?php
namespace App\Mappers;

use App\Enums\CredentialType;
use App\Models\Requests\RegisterRequest;

class RegisterRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return RegisterRequest
     */
    public function map($in)
    {
        $out = new RegisterRequest();

        $out->setEmailAddress(array_get($in, 'emailAddress', NULL));
        $out->setPassword(array_get($in, 'password', NULL));
        $out->setFirstName(array_get($in, 'firstName', NULL));
        $out->setLastName(array_get($in, 'lastName', NULL));
        $out->setCredentialType(array_get($in, 'credentialType', CredentialType::STANDARD));

        return $out;
    }
}