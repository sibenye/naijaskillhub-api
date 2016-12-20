<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserChangeEmailPost Request.
 *
 * @author silver.ibenye
 *
 */
class UserChangeEmailPostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $newEmailAddress;

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
                'newEmailAddress' => 'required|email|max:200'
        ];
    }

    /**
     * @return string
     */
    public function getNewEmailAddress()
    {
        return $this->newEmailAddress;
    }

    /**
     * @param  $newEmailAddress
     * @return void
     */
    public function setNewEmailAddress($newEmailAddress)
    {
        $this->newEmailAddress = $newEmailAddress;
    }
}
