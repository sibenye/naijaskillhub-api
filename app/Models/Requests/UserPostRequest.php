<?php

namespace App\Models\Requests;

use App\Enums\CredentialType;

class UserPostRequest implements IPostRequest {
    private $emailAddress;
    private $credentialType = CredentialType::STANDARD;
    private $password;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes() {
        $attr = array ();

        if (!empty($this->emailAddress)) { // checking for both NULL and zero length string.
            $attr ['emailAddress'] = $this->emailAddress;
        }
        if ($this->credentialType !== NULL) {
            $attr ['credentialType'] = $this->credentialType;
        }
        if ($this->password !== NULL) {
            $attr ['password'] = $this->password;
        }

        return $attr;
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules() {
        return [
                'emailAddress' => 'required',
                'password' => 'required'
        ];
    }

    public function getEmailAddress() {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getCredentialType() {
        return $this->credentialType;
    }

    public function setCredentialType($credentialType) {
        $this->credentialType = $credentialType;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

}