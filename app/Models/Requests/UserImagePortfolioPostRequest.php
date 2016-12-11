<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var UploadedFile
     */
    private $image;

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

        if (!empty($this->imageId)) {
            $attr ['imageId'] = $this->imageId;
        }
        if (!empty($this->image)) {
            $attr ['image'] = $this->image;
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
        return [ ];
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|NULL
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     *
     * @param UploadedFile|NULL $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
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
}
