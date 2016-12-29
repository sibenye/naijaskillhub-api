<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserAddAccountTypeRequest;

class UserAddAccountTypeRequestMappers implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserAddAccountTypeRequest
     */
    public function map($in)
    {
        $out = new UserAddAccountTypeRequest();

        $out->setAccountType(array_get($in, 'accountType', NULL));
    }
}

