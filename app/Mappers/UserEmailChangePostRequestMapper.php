<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserEmailChangePostRequest;

/**
 * UserEmailChangePostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserEmailChangePostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserEmailChangePostRequest
     */
    public function map($in)
    {
        $out = new UserEmailChangePostRequest();

        $out->setNewEmailAddress(array_get($in, 'newEmailAddress', NULL));

        return $out;
    }
}
