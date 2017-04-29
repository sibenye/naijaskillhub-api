<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\UserAudioPortfolioMetadataPostRequest;

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
     * @return UserAudioPortfolioMetadataPostRequest
     */
    public function map($in)
    {
        $out = new UserAudioPortfolioMetadataPostRequest();

        $out->setAudioId(array_get($in, 'audioId', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));
        $out->setDescription(array_get($in, 'description', NULL));
        $out->setRoleInTrack(array_get($in, 'roleInTrack', NULL));
        $out->setTrackType(array_get($in, 'trackType', NULL));

        return $out;
    }
}
