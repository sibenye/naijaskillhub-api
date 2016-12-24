<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\AddCredentialRequest;
use App\Enums\CredentialType;

/**
 * AddCredentialRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class AddCredentialRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return AddCredentialRequest
     */
    public function map($in)
    {
        $out = new AddCredentialRequest();

        $out->setEmailAddress(array_get($in, 'emailAddress', NULL));
        $out->setPassword(array_get($in, 'password', NULL));
        $out->setCredentialType(array_get($in, 'credentialType', CredentialType::STANDARD));

        return $out;
    }
}
