<?php
/**
 * @package App\Services
 */
namespace App\Services;

use App\Repositories\UserRepository;
use App\Utilities\NSHCryptoUtil;
use App\Utilities\NSHConstants;
use App\Models\Requests\LoginRequest;
use App\Enums\CredentialType;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NSHAuthenticationException;

/**
 * AuthService class.
 *
 * @author silver.ibenye
 *
 */
class AuthService
{

    /**
     *
     * @var NSHCryptoUtil
     */
    private $cryptoUtil;

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $repository, NSHCryptoUtil $cryptoUtil)
    {
        $this->userRepository = $repository;
        $this->cryptoUtil = $cryptoUtil;
    }

    /**
     * Generate user authentication token.
     *
     * @return string
     */
    public function generateAuthToken()
    {
        $authToken = null;
        do {
            $authToken = $this->cryptoUtil->secureRandomString(NSHConstants::AUTH_TOKEN_LENGTH);
        } while ( !empty($this->userRepository->getUserByAuthToken($authToken)) );

        return $authToken;
    }

    /**
     *
     * @param LoginRequest $request
     * @return array
     * @throws NSHAuthenticationException
     */
    public function login(LoginRequest $request)
    {
        $user = $this->userRepository->getUserByEmailAddress($request->getEmailAddress());

        if (empty($user)) {
            throw new NSHAuthenticationException('Invalid emailAddress');
        }

        $existingUserCredentials = $this->userRepository->getUserCredentials($user->id,
                $request->getCredntialType());

        var_dump($existingUserCredentials);
        if (count($existingUserCredentials) == 0) {
            throw new NSHAuthenticationException(
                'User does not have ' . $request->getCredntialType() . ' credential.');
        }

        if ($request->getCredntialType() == CredentialType::STANDARD) {

            // verify password
            if (!$this->cryptoUtil->hashMatches($request->getPassword(),
                    $existingUserCredentials [0]->pivot->password)) {
                throw new NSHAuthenticationException('Invalid password');
            }
        }
        // TODO: handle google and facebook credentials

        // generate AuthToken
        $userModelAttr = array ();
        $userModelAttr ['authToken'] = $this->generateAuthToken();

        $this->userRepository->update($user->id, $userModelAttr);

        $response = array ();
        $response ['authToken'] = $userModelAttr ['authToken'];

        return $response;
    }

    /**
     *
     * @param string $emailAddress
     * @return void
     * @throws NSHAuthenticationException
     */
    public function logout($emailAddress)
    {
        if (empty($emailAddress)) {
            throw new ValidationException(null, 'emailAddress is required');
        }

        $user = $this->userRepository->getUserByEmailAddress($emailAddress);

        if (empty($user)) {
            throw new NSHAuthenticationException('Invalid emailAddress');
        }
        // clear AuthToken
        $userModelAttr = array ();
        $userModelAttr ['authToken'] = '';

        $this->userRepository->update($user->id, $userModelAttr);
    }
}
