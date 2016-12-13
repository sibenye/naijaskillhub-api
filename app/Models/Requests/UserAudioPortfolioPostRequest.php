<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserAudioPortfolioPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserAudioPortfolioPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $audioId;

    /**
     *
     * @var UploadedFile
     */
    private $audio;

    /**
     *
     * @var string
     */
    private $caption;

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
        if (!empty($this->audio)) {
            $attr ['audio'] = $this->audio;
        }
        if ($this->caption !== NULL) {
            $attr ['caption'] = $this->caption;
        }

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     * @return void
     */
    public function getValidationRules()
    {
        return [ ];
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
     * @return the UploadedFile
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param UploadedFile $audio
     * @return void
     */
    public function setAudio(UploadedFile $audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return the string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param  $caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }
}
