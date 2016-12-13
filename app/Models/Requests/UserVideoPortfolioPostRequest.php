<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserVideoPortfolioPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserVideoPortfolioPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $videoId;

    /**
     *
     * @var string
     */
    private $videoUrl;

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

        if (!empty($this->videoId)) {
            $attr ['videoId'] = $this->videoId;
        }
        if ($this->videoUrl !== NULL) {
            $attr ['videoUrl'] = $this->videoUrl;
        }
        if ($this->caption !== NULL) {
            $attr ['caption'] = $this->caption;
        }

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'caption' => 'max:200'
        ];
    }

    /**
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     *
     * @param string $caption
     * @return void
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * @param  $videoId
     * @return void
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }

    /**
     * @param string $videoUrl
     * @return void
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;
    }
}
