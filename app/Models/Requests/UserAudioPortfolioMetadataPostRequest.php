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
                'caption' => 'max:200',
                'audioId' => 'required|max:20'
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
}
