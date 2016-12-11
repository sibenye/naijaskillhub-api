<?php
namespace App\Mappers;

use App\Models\Requests\UserPostRequest;
use App\Enums\CredentialType;

class UserPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserPostRequest
     */
    public function map($in)
    {
        $out = new UserPostRequest();

        $out->setEmailAddress(array_get($in, 'emailAddress', NULL));
        $out->setPassword(array_get($in, 'password', NULL));
        $out->setCredentialType(array_get($in, 'credentialType', CredentialType::STANDARD));

        return $out;
    }
}