<?php
/**
 * @package App\Exceptions
 */
namespace App\Exceptions;

/**
 * NSH Exception Interface.
 *
 * @author silver.ibenye
 *
 */
interface INSHException
{

    /**
     * @return string
     */
    public function getErrorMessage();

    /**
     * @return integer
     */
    public function getHttpStatus();
}
