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
class UserImagePortfolioMetadataPostRequest implements IPostRequest
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
                'imageId' => 'required'
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
}
