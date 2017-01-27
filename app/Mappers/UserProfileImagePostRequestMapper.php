<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserImagePortfolioPostRequest;
use App\Models\Requests\UserProfileImagePostRequest;

/**
 * UserImagePortfolioPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserProfileImagePostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserProfileImagePostRequest
     */
    public function map($in)
    {
        $out = new UserProfileImagePostRequest();

        $out->setImage(array_get($in, 'image', NULL));

        return $out;
    }
}
