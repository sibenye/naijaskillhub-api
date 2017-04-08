<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserProfileImagePost Request.
 *
 * @author silver.ibenye
 *
 */
class FileUploadRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $file;

    /**
     *
     * @var string
     */
    private $contentType;

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

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     * @return array
     */
    public function getValidationRules()
    {
        return [ ];
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param  $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param  $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }
}
