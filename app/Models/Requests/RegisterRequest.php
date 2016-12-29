<?php
namespace App\Models\Requests;

use App\Enums\CredentialType;
use App\Enums\AccountType;

/**
 * Register Request.
 *
 * @author silver.ibenye
 *
 */
class RegisterRequest implements IPostRequest
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
    private $credentialType = CredentialType::STANDARD;

    /**
     *
     * @var string
     */
    private $password;

    /**
     *
     * @var string
     */
    private $firstName;

    /**
     *
     * @var string
     */
    private $lastName;

    /**
     *
     * @var string
     */
    private $accountType = AccountType::TALENT;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        $attr = array ();

        if (!empty($this->emailAddress)) {
            $attr ['emailAddress'] = $this->emailAddress;
        }
        if ($this->credentialType !== NULL) {
            $attr ['credentialType'] = $this->credentialType;
        }
        if ($this->password !== NULL) {
            $attr ['password'] = $this->password;
        }

        $attr ['accountType'] = $this->accountType;

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'emailAddress' => 'required|email|max:200',
                'password' => 'required|max:200|min:8',
                'firstName' => 'max:80',
                'lastName' => 'max:80',
                'credentialType' => 'in:' . CredentialType::STANDARD . ',' . CredentialType::FACEBOOK .
                         ',' . CredentialType::GOOGLE,
                        'accountType' => 'in:' . AccountType::TALENT . ',' . AccountType::HUNTER
        ];
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getCredentialType()
    {
        return $this->credentialType;
    }

    public function setCredentialType($credentialType)
    {
        $this->credentialType = $credentialType;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param  $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param  $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param  $accountType
     * @return void
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }
}
