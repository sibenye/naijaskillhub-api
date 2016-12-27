<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserChangeVanityNamePostRequest;

/**
 * UserChangeVanityNamePostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserChangeVanityNamePostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserChangeVanityNamePostRequest
     */
    public function map($in)
    {
        $out = new UserChangeVanityNamePostRequest();

        $out->setNewVanityName(array_get($in, 'newVanityName', NULL));

        return $out;
    }
}
