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
     * @var integer
     */
    private $contentLength;

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
     * @return integer
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }

    /**
     * @param integer $contentLength
     */
    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
    }
}
