<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserAudioPortfolioPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserAudioPortfolioMetadataPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $audioId;

    /**
     *
     * @var string
     */
    private $caption;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var string
     */
    private $trackType;

    /**
     *
     * @var string
     */
    private $roleInTrack;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        $attr = array ();

        if (!empty($this->audioId)) {
            $attr ['audioId'] = $this->audioId;
        }
        if ($this->caption !== NULL) {
            $attr ['caption'] = $this->caption;
        }

        if ($this->description !== NULL) {
            $attr ['description'] = $this->description;
        }

        if ($this->trackType !== NULL) {
            $attr ['trackType'] = $this->trackType;
        }

        if ($this->roleInTrack !== NULL) {
            $attr ['roleInTrack'] = $this->roleInTrack;
        }

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     * @return array
     */
    public function getValidationRules()
    {
        return [
                'caption' => 'max:200',
                'audioId' => 'required|max:20',
                'description' => 'max:500',
                'trackType' => 'max:40',
                'roleInTrack' => 'max:45'
        ];
    }

    /**
     * @return the string
     */
    public function getAudioId()
    {
        return $this->audioId;
    }

    /**
     * @param  $audioId
     * @return void
     */
    public function setAudioId($audioId)
    {
        $this->audioId = $audioId;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param  $caption
     *
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTrackType()
    {
        return $this->trackType;
    }

    /**
     * @param  $trackType
     *
     * @return void
     */
    public function setTrackType($trackType)
    {
        $this->trackType = $trackType;
    }

    /**
     * @return string
     */
    public function getRoleInTrack()
    {
        return $this->roleInTrack;
    }

    /**
     * @param  $roleInTrack
     *
     * @return void
     */
    public function setRoleInTrack($roleInTrack)
    {
        $this->roleInTrack = $roleInTrack;
    }
}
