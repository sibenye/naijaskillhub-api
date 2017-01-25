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
use App\Models\Requests\UserPostRequest;
use App\Models\Requests\RegisterRequest;
use App\Repositories\CredentialTypeRepository;
use App\Repositories\UserAttributeRepository;
use App\Repositories\AccountTypeRepository;

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

    /**
     *
     * @var CredentialTypeRepository
     */
    private $credentialTypeRepository;

    /**
     *
     * @var AccountTypeRepository
     */
    private $accountTypeRepository;

    /**
     *
     * @var UserAttributeRepository
     */
    private $userAttributeRepository;

    public function __construct(UserRepository $repository,
            CredentialTypeRepository $credentialTypeRepository,
            UserAttributeRepository $userAttributeRepository,
            AccountTypeRepository $accountTypeRepository, NSHCryptoUtil $cryptoUtil)
    {
        $this->userRepository = $repository;
        $this->cryptoUtil = $cryptoUtil;
        $this->credentialTypeRepository = $credentialTypeRepository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->accountTypeRepository = $accountTypeRepository;
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
            throw new ValidationException(NULL, 'Invalid emailAddress');
        }

        $existingUserCredentials = $this->userRepository->getUserCredentials($user->id,
                $request->getCredntialType());

        if (count($existingUserCredentials) == 0) {
            throw new ValidationException(NULL,
                'User does not have ' . $request->getCredntialType() . ' credential.');
        }

        // verify password
        if ($request->getCredntialType() == CredentialType::STANDARD) {
            if (!$this->cryptoUtil->hashMatches($request->getPassword(),
                    $existingUserCredentials [0]->pivot->password)) {
                throw new ValidationException(NULL, 'Invalid password');
            }
        } else {
            if ($request->getPassword() != $existingUserCredentials [0]->pivot->password) {
                throw new ValidationException(NULL, 'Invalid social Identifier');
            }
        }

        $response = array ();
        $response ['authToken'] = $this->generateAuthToken($request->getEmailAddress());
        $response ['userId'] = $user->id;

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
            throw new ValidationException(NULL,
                'Invalid emailAddress. No User with emailAddress was found');
        }
        // clear AuthToken
        $userModelAttr = array ();
        $userModelAttr ['authToken'] = '';

        $this->userRepository->update($user->id, $userModelAttr);
    }

    /**
     * Register/Create a User.
     *
     * @param array $request
     * @throws ValidationException
     * @return array Associative array.
     */
    public function register(RegisterRequest $request)
    {
        // ensure emailAddress is not already in use
        if ($this->userRepository->getUserByEmailAddress($request->getEmailAddress())) {
            throw new ValidationException(NULL, 'The emailAddress is already in use.');
        }

        // ensure credentialType is valid
        $credentialType = $this->credentialTypeRepository->getCredentialTypeByName(
                $request->getCredentialType());
        if (empty($credentialType)) {
            throw new ValidationException(NULL, 'The credentialType is invalid.');
        }

        // ensure accountType is valid
        $accountType = $this->accountTypeRepository->getAccountTypeByName(
                $request->getAccountType());
        if (empty($accountType)) {
            throw new ValidationException(NULL, 'The accountType is invalid.');
        }

        if ($request->getCredentialType() == CredentialType::STANDARD) {
            $request->setPassword($this->cryptoUtil->hashThis($request->getPassword()));
        }

        $userModelAttr = $request->buildModelAttributes();

        $userModelAttr ['credentialTypeId'] = $credentialType->id;
        $userModelAttr ['accountTypeId'] = $accountType->id;

        $user = $this->userRepository->createUser($userModelAttr);

        if (!empty($request->getFirstName()) || !empty($request->getLastName())) {
            $attributesCollection = array ();
            if (!empty($request->getFirstName())) {
                $firstNameAttr = array ();
                $userAttribute = $this->userAttributeRepository->getUserAttributeByName('firstName');

                if (!empty($userAttribute)) {
                    $firstNameAttr ['attributeId'] = $userAttribute->id;
                    $firstNameAttr ['attributeValue'] = $request->getFirstName();
                    $attributesCollection [] = $firstNameAttr;
                }
            }
            if (!empty($request->getLastName())) {
                $lastNameAttr = array ();
                $userAttribute = $this->userAttributeRepository->getUserAttributeByName('lastName');

                if (!empty($userAttribute)) {
                    $lastNameAttr ['attributeId'] = $userAttribute->id;
                    $lastNameAttr ['attributeValue'] = $request->getLastName();
                    $attributesCollection [] = $lastNameAttr;
                }
            }

            if (!empty($attributesCollection)) {
                $this->userRepository->upsertUserAttributeValue($user, $attributesCollection);
            }
        }

        $user ['authToken'] = $this->generateAuthToken($request->getEmailAddress());

        return $user;
    }

    /**
     * Generate user authentication token.
     *
     * @return string
     */
    private function generateAuthToken($emailAddress)
    {
        return $this->cryptoUtil->generateJWToken($emailAddress);
    }
}
