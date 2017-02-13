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
class UserAudioPortfolioPostRequest implements IPostRequest
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
    private $uploadContentType;

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
                'caption' => 'required|max:200',
                'uploadContentType' => 'required|max:20'
        ];
    }

    /**
     *
     * @return array
     */
    public function getUpdateValidationRules()
    {
        return [
                'audioId' => 'required',
                'caption' => 'required|max:200'
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function getCustomMessages()
    {
        return [
                'uploadContentType.required' => 'uploadContentType is required. And should be in this format "audio/{audio extension}"'
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
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getUploadContentType()
    {
        return $this->uploadContentType;
    }

    /**
     * @param string $uploadContentType
     * return void
     */
    public function setUploadContentType($uploadContentType)
    {
        $this->uploadContentType = $uploadContentType;
    }
}
