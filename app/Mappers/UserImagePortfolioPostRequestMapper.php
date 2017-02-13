<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserImagePortfolioPostRequest;

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
     * @return UserImagePortfolioPostRequest
     */
    public function map($in)
    {
        $out = new UserImagePortfolioPostRequest();

        $out->setImageId(array_get($in, 'imageId', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));
        $out->setUploadContentType(array_get($in, 'uploadContentType', NULL));

        return $out;
    }
}
