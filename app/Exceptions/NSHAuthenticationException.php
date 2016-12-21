<?php
/**
 * @package App\Exceptions
 */
namespace App\Exceptions;

use Exception;

/**
 * NSHAuthentication Exception.
 *
 * @author silver.ibenye
 *
 */
class NSHAuthenticationException extends Exception implements INSHException
{
    /**
     *
     * @var string
     */
    private $errorMessage;

    /**
     *
     * @var integer
     */
    private $httpStatus = 401;

    public function __construct($errorMessage)
    {
        parent::__construct($errorMessage);
        $this->errorMessage = $errorMessage;
    }

    /**
     * {@inheritDoc}
     * @see \App\Exceptions\INSHException::getMessage()
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * {@inheritDoc}
     * @see \App\Exceptions\INSHException::getHttpStatus()
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}
