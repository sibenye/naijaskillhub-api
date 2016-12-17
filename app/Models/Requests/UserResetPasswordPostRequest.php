<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserResetPassword Post Request.
 *
 * @author silver.ibenye
 *
 */
class UserResetPasswordPostRequest implements IPostRequest
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
    private $resetToken;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     * @return array
     */
    public function buildModelAttributes()
    {
        return [
                'newPassword' => $this->newPassword,
                'resetToken' => $this->resetToken
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
                'resetToken' => 'required|max:250'
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
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * @param  $resetToken
     * @return void
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }
}