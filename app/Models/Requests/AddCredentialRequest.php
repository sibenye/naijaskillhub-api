<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

use App\Enums\CredentialType;

/**
 * Add Credential Request
 *
 * @author silver.ibenye
 *
 */
class AddCredentialRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $emailAddress;

    /**
     *
     * @var string
     */
    private $password;

    /**
     *
     * @var CredentialType
     */
    private $credentialType = CredentialType::STANDARD;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        // TODO: Auto-generated method stub
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'emailAddress' => 'required|email',
                'password' => 'required',
                'credentialType' => 'in:' . CredentialType::STANDARD . ',' . CredentialType::FACEBOOK .
                         ',' . CredentialType::GOOGLE
        ];
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

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param  $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return CredentialType
     */
    public function getCredntialType()
    {
        return $this->credentialType;
    }

    /**
     * @param CredentialType $credentialType
     * @return void
     */
    public function setCredentialType($credentialType)
    {
        $this->credentialType = $credentialType;
    }
}
