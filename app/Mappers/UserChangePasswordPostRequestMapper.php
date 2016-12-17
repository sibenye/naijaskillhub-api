<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserChangePasswordPostRequest;

/**
 * UserChangePasswordPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserChangePasswordPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array
     * @return UserChangePasswordPostRequest
     */
    public function map($in)
    {
        $out = new UserChangePasswordPostRequest();

        $out->setNewPassword(array_get($in, 'newPassword', NULL));
        $out->setOldPassword(array_get($in, 'oldPassword', NULL));

        return $out;
    }
}
