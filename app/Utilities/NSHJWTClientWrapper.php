<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

/**
 * Lcobucci\JWT Client Wrapper.
 *
 * @author silver.ibenye
 *
 */
class NSHJWTClientWrapper
{
    private $secretKey;
    private $issuer;
    private $audience;
    private $jti;
    private $duration;
    private $signer;

    public function __construct()
    {
        $this->jti = env("JWT_ID");
        $this->secretKey = env("APP_KEY");
        $this->issuer = env("JWT_ISSUER");
        $this->audience = env("JWT_AUDIENCE");
        $this->duration = env("JWT_DURATION");
        $this->signer = new Sha256();
    }

    /**
     * Generates a Json Web Token.
     *
     * @param string $emailAddress
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken($emailAddress)
    {
        $now = time();
        $builder = new Builder();
        $token = $builder->setIssuer($this->issuer)
            ->setAudience($this->audience)
            ->setId($this->jti, true)
            ->setIssuedAt($now)
            ->setNotBefore($now)
            ->setExpiration($now + $this->duration)
            ->set('email', $emailAddress)
            ->sign($this->signer, $this->secretKey)
            ->getToken();

        return $token->__toString();
    }

    /**
     *
     * @param Token $token
     * @return boolean
     */
    public function tokenIsExpired(Token $token)
    {
        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer($this->issuer);
        $data->setAudience($this->audience);
        $data->setId($this->jti);

        return !$token->validate($data);
    }

    /**
     * Verify Token's signature.
     *
     * @param Token $token
     * @return boolean
     */
    public function verifyToken(Token $token)
    {
        return $token->verify($this->signer, $this->secretKey);
    }

    /**
     * Convert string token to Lcobucci\JWT\Token.
     *
     * @param string $token
     * @return \Lcobucci\JWT\Token
     */
    public function parseToken($token)
    {
        $parser = new Parser();
        $token = $parser->parse(( string ) $token); // Parses from a string
        return $token;
    }
}
