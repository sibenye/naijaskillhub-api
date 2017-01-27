<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserProfileImagePost Request.
 *
 * @author silver.ibenye
 *
 */
class UserProfileImagePostRequest implements IPostRequest
{
    /**
     *
     * @var UploadedFile
     */
    private $image;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        $attr = array ();

        if (!empty($this->image)) {
            $attr ['image'] = $this->image;
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
                'image' => 'required'
        ];
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
}
