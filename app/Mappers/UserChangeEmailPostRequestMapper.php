<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserChangeEmailPostRequest;

/**
 * UserChangeEmailPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserChangeEmailPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserChangeEmailPostRequest
     */
    public function map($in)
    {
        $out = new UserChangeEmailPostRequest();

        $out->setNewEmailAddress(array_get($in, 'newEmailAddress', NULL));

        return $out;
    }
}
