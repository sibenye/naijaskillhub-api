<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserVideoPortfolioPostRequest;

/**
 * UserVideoPortfolioPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserVideoPortfolioPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserVideoPortfolioPostRequest
     */
    public function map($in)
    {
        $out = new UserVideoPortfolioPostRequest();

        $out->setVideoId(array_get($in, 'videoId', NULL));
        $out->setVideoUrl(array_get($in, 'videoUrl', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));

        return $out;
    }
}
