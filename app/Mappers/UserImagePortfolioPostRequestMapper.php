<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserImagePortfolioMetadataPostRequest;

/**
 * UserImagePortfolioPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserImagePortfolioPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserImagePortfolioMetadataPostRequest
     */
    public function map($in)
    {
        $out = new UserImagePortfolioMetadataPostRequest();

        $out->setImageId(array_get($in, 'imageId', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));

        return $out;
    }
}
