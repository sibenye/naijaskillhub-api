<?php
/**
 * NSH_CryptoUtil.
 * Provides crypto utilities such as encryption, hashing etc.
 */
namespace App\Utilities;

/**
 * NSH_CryptoUtil Class.
 *
 * @author silver ibenye
 *
 */
class NSHCryptoUtil
{

    /**
     * Hash a string.
     *
     * @param string $secret The string to hash.
     * @return string Hashed value.
     */
    public function hashThis($secret)
    {
        return app('hash')->make($secret);
    }
}