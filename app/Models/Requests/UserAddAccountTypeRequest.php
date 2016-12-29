<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

use App\Enums\AccountType;

/**
 * UserAccountType Request
 *
 * @author silver.ibenye
 *
 */
class UserAddAccountTypeRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $accountType;

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
                'accountType' => 'required|in:' . AccountType::HUNTER . ',' . AccountType::TALENT
        ];
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param string $accountType
     * @return void
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }
}
