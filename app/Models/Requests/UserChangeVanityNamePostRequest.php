<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * UserChangeVanityName Request.
 *
 * @author silver.ibenye
 *
 */
class UserChangeVanityNamePostRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $newVanityName;

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
                'newVanityName' => 'required|max:45'
        ];
    }

    /**
     * @return string
     */
    public function getNewVanityName()
    {
        return $this->newVanityName;
    }

    /**
     * @param  $newVanityName
     * @return void
     */
    public function setNewVanityName($newVanityName)
    {
        $this->newVanityName = $newVanityName;
    }
}
