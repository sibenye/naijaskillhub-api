<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserForgotPasswordPostRequest;

/**
 * UserForgotPasswordPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserForgotPasswordPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array
     * @return UserForgotPasswordPostRequest
     */
    public function map($in)
    {
        $out = new UserForgotPasswordPostRequest();

        $out->setResetToken(array_get($in, 'resetToken', NULL));
        $out->setEmailAddress(array_get($in, 'emailAddress', NULL));

        return $out;
    }
}
