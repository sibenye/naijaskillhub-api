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

    public function hashMatches($secret, $hash)
    {
        return app('hash')->check($secret, $hash);
    }

    public function secureRandomString($length)
    {
        $factory = new \RandomLib\Factory();
        $generator = $factory->getLowStrengthGenerator();
        return $generator->generateString($length);
    }
}