<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserForgotPassword Post Request.
 *
 * @author silver.ibenye
 *
 */
class UserForgotPasswordPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $resetToken;

    /**
     *
     * @var string
     */
    private $emailAddress;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     * @return array
     */
    public function buildModelAttributes()
    {
        return [
                'resetToken' => $this->resetToken,
                'emailAddress' => $this->emailAddress
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
                'resetToken' => 'required|max:250',
                'emailAddress' => 'required'
        ];
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

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param  $emailAddress
     * @return void
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
}