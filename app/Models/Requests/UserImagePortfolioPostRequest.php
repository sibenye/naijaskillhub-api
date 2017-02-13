<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserImagePortfolioPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserImagePortfolioPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $imageId;

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

        if (!empty($this->imageId)) {
            $attr ['imageId'] = $this->imageId;
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
                'imageId' => 'required',
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
                'uploadContentType.required' => 'uploadContentType is required. And should be in this format "image/{image extension}"'
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
     * @return the string
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * @param  $imageId
     * @return void
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     *
     * @return string
     */
    public function getUploadContentType()
    {
        return $this->uploadContentType;
    }

    /**
     *
     * @param string $uploadContentType
     * return void
     */
    public function setUploadContentType($uploadContentType)
    {
        $this->uploadContentType = $uploadContentType;
    }
}
