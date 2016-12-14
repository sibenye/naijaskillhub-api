<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserAudioPortfolioPostRequest;

/**
 * UserAudioPortfolioPostRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class UserAudioPortfolioPostRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return UserAudioPortfolioPostRequest
     */
    public function map($in)
    {
        $out = new UserAudioPortfolioPostRequest();

        $out->setAudioId(array_get($in, 'audioId', NULL));
        $out->setAudio(array_get($in, 'audio', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));

        return $out;
    }
}
