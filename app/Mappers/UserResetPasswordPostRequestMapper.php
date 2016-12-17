<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserResetPasswordPostRequest;

/**
 * UserResetPasswordPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserResetPasswordPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array
     * @return UserResetPasswordPostRequest
     */
    public function map($in)
    {
        $out = new UserResetPasswordPostRequest();

        $out->setNewPassword(array_get($in, 'newPassword', NULL));
        $out->setResetToken(array_get($in, 'resetToken', NULL));

        return $out;
    }
}
