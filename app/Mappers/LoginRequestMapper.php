<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Enums\CredentialType;
use App\Models\Requests\LoginRequest;

/**
 * LoginRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class LoginRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return LoginRequest
     */
    public function map($in)
    {
        $out = new LoginRequest();

        $out->setEmailAddress(array_get($in, 'emailAddress', NULL));
        $out->setPassword(array_get($in, 'password', NULL));
        $out->setCredentialType(array_get($in, 'credentialType', CredentialType::STANDARD));

        return $out;
    }
}