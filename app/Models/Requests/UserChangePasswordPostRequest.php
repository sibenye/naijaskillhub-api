<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserChangePassword Post Request.
 *
 * @author silver.ibenye
 *
 */
class UserChangePasswordPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $newPassword;

    /**
     *
     * @var string
     */
    private $oldPassword;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     * @return array
     */
    public function buildModelAttributes()
    {
        return [
                'newPassword' => $this->newPassword,
                'oldPassword' => $this->oldPassword
        ];
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     * @return array
     */
    public function getValidationRules()
    {
        return [
                'newPassword' => 'required|max:200',
                'oldPassword' => 'required|max:200'
        ];
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param  $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param  $oldPassword
     * @return void
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}