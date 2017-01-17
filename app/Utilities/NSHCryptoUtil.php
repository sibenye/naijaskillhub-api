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
     *
     * @var NSHJWTClientWrapper
     */
    private $jwtClientWrapper;

    public function __construct(NSHJWTClientWrapper $jwtClientWrapper)
    {
        $this->jwtClientWrapper = $jwtClientWrapper;
    }

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

    /**
     *
     * @param string $secret
     * @param string $hash
     * @return boolean
     */
    public function hashMatches($secret, $hash)
    {
        return app('hash')->check($secret, $hash);
    }

    /**
     *
     * @param int $length
     * @return string
     */
    public function secureRandomString($length)
    {
        $factory = new \RandomLib\Factory();
        $generator = $factory->getLowStrengthGenerator();
        return $generator->generateString($length);
    }

    public function generateJWToken($emailAddress)
    {
        return $this->jwtClientWrapper->generateToken($emailAddress);
    }
}
