<?php
/**
 *
 * @package App\Services
 */
namespace App\Services;

use App\Enums\CredentialType;
use App\Repositories\CategoryRepository;
use App\Repositories\CredentialTypeRepository;
use App\Repositories\UserAttributeRepository;
use App\Repositories\UserRepository;
use App\Utilities\NSHCryptoUtil;
use Illuminate\Validation\ValidationException;
use App\Models\Requests\UserChangePasswordPostRequest;
use App\Models\Requests\UserResetPasswordPostRequest;
use App\Models\Requests\UserPostRequest;
use App\Models\Requests\UserChangeEmailPostRequest;

/**
 * UserService class.
 *
 * @author silver.ibenye
 *
 */
class UserService
{
    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var UserAttributeRepository
     */
    private $userAttributeRepository;

    /**
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     *
     * @var CredentialTypeRepository
     */
    private $credentialTypeRepository;

    /**
     *
     * @var NSHCryptoUtil
     */
    private $cryptoUtil;

    /**
     *
     * @param UserRepository $repository
     * @param UserAttributeRepository $userAttributeRepository
     * @param CategoryRepository $categoryRepository
     * @param CredentialTypeRepository $credentialTypeRepository
     * @param NSH_CryptoUtil $cryptoUtil
     */
    public function __construct(UserRepository $repository,
            UserAttributeRepository $userAttributeRepository, CategoryRepository $categoryRepository,
            CredentialTypeRepository $credentialTypeRepository, NSHCryptoUtil $cryptoUtil)
    {
        $this->userRepository = $repository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->credentialTypeRepository = $credentialTypeRepository;
        $this->cryptoUtil = $cryptoUtil;
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function getUser($id)
    {
        $user = $this->userRepository->get($id);

        $userContent = array ();
        $userContent ['userId'] = $user->id;
        $userContent ['isActive'] = $user->isActive;
        $userContent ['emailAddress'] = $user->emailAddress;
        $userContent ['createdDate'] = $user->createdDate->toDateTimeString();
        $userContent ['modifiedDate'] = $user->modifiedDate->toDateTimeString();

        return $userContent;
    }

    /**
     *
     * @param integer $userId
     * @param array $requestedAttributes
     * @return array
     */
    public function getUserAttributes($userId, $requestedAttributes = [])
    {
        $userAttributes = $this->userRepository->getUserAttributes($userId, $requestedAttributes);

        $userAttributesContent = array ();
        $i = 0;

        foreach ($userAttributes as $value) {
            $userAttributesContent [$i] ['attributeId'] = $value->id;
            $userAttributesContent [$i] ['attributeName'] = $value->name;
            $userAttributesContent [$i] ['attributeValue'] = $value->pivot->attributeValue;
            $userAttributesContent [$i] ['createdDate'] = $value->pivot->createdDate;
            $userAttributesContent [$i] ['modifiedDate'] = $value->pivot->modifiedDate;

            $i++;
        }

        $result = array ();
        $result ['userId'] = $userId;
        $result ['attributes'] = $userAttributesContent;

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function getUserCategories($id)
    {
        $user = $this->userRepository->get($id);

        $userCategories = $user->categories;

        $userCategoriesContent = array ();

        foreach ($userCategories as $key => $value) {
            $userCategoriesContent [$key] ['categoryId'] = $value->id;
            $userCategoriesContent [$key] ['categoryName'] = $value->name;
        }

        $result = array ();
        $result ['userId'] = $id;
        $result ['categories'] = $userCategoriesContent;

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function getUserCredentialTypes($id)
    {
        $user = $this->userRepository->get($id);

        $userCredentialTypes = $user->credentialTypes;

        $userCredentialTypesContent = array ();

        foreach ($userCredentialTypes as $key => $value) {
            $userCredentialTypesContent [$key] ['credentialTypeId'] = $value->id;
            $userCredentialTypesContent [$key] ['credentialTypeName'] = $value->name;
            $userCredentialTypesContent [$key] ['createdDate'] = $value->pivot->createdDate;
            $userCredentialTypesContent [$key] ['modifiedDate'] = $value->pivot->modifiedDate;
        }

        $result = array ();
        $result ['userId'] = $id;
        $result ['credentialTypes'] = $userCredentialTypesContent;

        return $result;
    }

    /**
     *
     * @param integer $userId
     * @param array $userAttributeValuePostRequest
     * @return void
     */
    public function upsertUserAttributeValue($userId, $userAttributeValuePostRequest)
    {
        // validate user Id
        $user = $this->userRepository->get($userId);

        // validate the attribute names in the request
        $attributesCollection = array ();
        $i = 0;

        foreach ($userAttributeValuePostRequest as $attributeName => $attributeValue) {
            $i++;
            $userAttribute = $this->userAttributeRepository->getUserAttributeByName($attributeName,
                    true);
            $attributesCollection [$i] ['attributeId'] = $userAttribute ['id'];
            $attributesCollection [$i] ['attributeValue'] = $attributeValue;
        }

        $this->userRepository->upsertUserAttributeValue($user, $attributesCollection);
    }

    /**
     *
     * @param integer $userId
     * @param array $categoriesRequest
     * @return void
     */
    public function linkUserToCategory($userId, $categoriesRequest)
    {
        // ensure the categoryIds are valid
        foreach ($categoriesRequest as $request) {
            $this->categoryRepository->get($request ['categoryId']);
        }

        $this->userRepository->linkUserToCategory($userId, $categoriesRequest);
    }

    /**
     *
     * @param integer $userId
     * @param array $categoriesRequest
     * @return void
     */
    public function unlinkUserFromCategory($userId, $categoriesRequest)
    {
        // ensure the categoryIds are valid
        foreach ($categoriesRequest as $request) {
            $this->categoryRepository->get($request ['categoryId']);
        }

        $this->userRepository->unlinkUserFromCategory($userId, $categoriesRequest);
    }

    /**
     * Register or Create a User.
     *
     * @param array $request
     * @throws ValidationException
     * @return array Associative array.
     */
    public function registerUser(UserPostRequest $request)
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

        if ($request->getCredentialType() == CredentialType::STANDARD) {
            $request->setPassword($this->cryptoUtil->hashThis($request->getPassword()));
        }

        $userModelAttr = $request->buildModelAttributes();

        $userModelAttr ['credentialTypeId'] = $credentialType->id;

        $user = $this->userRepository->createUser($userModelAttr);

        return $user;
    }

    /**
     *
     * @param integer $userId
     * @param UserChangePasswordPostRequest $request
     * @return void
     * @throws ValidationException
     */
    public function changeUserPassword($userId, UserChangePasswordPostRequest $request)
    {
        // verify user
        $user = $this->userRepository->get($userId);

        // password change is only for standard credentials.
        $existingUserCredentials = $this->userRepository->getUserCredentials($userId, 'standard');

        if (empty($existingUserCredentials)) {
            throw new ValidationException(null, 'User does not have standard credential.');
        }

        // verify old password
        if (!$this->cryptoUtil->hashMatches($request->getOldPassword(),
                $existingUserCredentials [0]->pivot->password)) {
            throw new ValidationException(null, 'Invalid old password');
        }

        $newPasswordHash = $this->cryptoUtil->hashThis($request->getNewPassword());

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName('standard')->id;

        $userCred = array ();
        $userCred ['credentialType'] = 'standard';
        $userCred ['credentialTypeId'] = $credentialTypeId;
        $userCred ['password'] = $newPasswordHash;

        $this->userRepository->upsertUserCredential($user, $userCred);
    }

    /**
     *
     * @param integer $userId
     * @param UserResetPasswordPostRequest $request
     * @return void
     * @throws ValidationException
     */
    public function resetUserPassword($userId, UserResetPasswordPostRequest $request)
    {
        // verify user
        $user = $this->userRepository->get($userId);

        // password reset is only for standard credentials.
        $existingUserCredentials = $this->userRepository->getUserCredentials($userId, 'standard');

        if (empty($existingUserCredentials)) {
            throw new ValidationException(null, 'User does not have standard credential.');
        }

        // validate reset token
        if ($user->resetToken != $request->getResetToken()) {
            throw new ValidationException(null, 'resetToken is invalid.');
        }

        $newPasswordHash = $this->cryptoUtil->hashThis($request->getNewPassword());

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName('standard')->id;

        $userCred = array ();
        $userCred ['credentialType'] = 'Standard';
        $userCred ['credentialTypeId'] = $credentialTypeId;
        $userCred ['password'] = $newPasswordHash;

        $this->userRepository->upsertUserCredential($user, $userCred);

        // clear the resetToken
        $userUpdateAttributes = array ();
        $userUpdateAttributes ['resetToken'] = '';

        $this->userRepository->update($userId, $userUpdateAttributes);
    }

    /**
     *
     * @param integer $userId
     * @param string $resetToken
     * @return void
     * @throws ValidationException
     */
    public function insertResetToken($userId, $resetToken)
    {
        if (empty($resetToken)) {
            throw new ValidationException(null, 'resetToken is required');
        }

        // verify user
        $this->userRepository->get($userId);

        // save the resetToken
        $updateAttributes = array ();
        $updateAttributes ['resetToken'] = $resetToken;

        $this->userRepository->update($userId, $updateAttributes);
    }

    /**
     *
     * @param integer $userId
     * @return void
     */
    public function activateUser($userId)
    {
        // verify user
        $this->userRepository->get($userId);

        // activate user
        $updateAttributes = array ();
        $updateAttributes ['isActive'] = true;

        $this->userRepository->update($userId, $updateAttributes);
    }

    /**
     *
     * @param integer $userId
     * @param UserChangeEmailPostRequest $request
     * @return void
     */
    public function changeUserEmailAddress($userId, UserChangeEmailPostRequest $request)
    {
        // verify user
        $user = $this->userRepository->get($userId);

        if (strtolower($user->emailAddress) != strtolower($request->getNewEmailAddress())) {
            $modelAttr = [
                    'emailAddress' => $request->getNewEmailAddress(),
                    'isActive' => false
            ];
            $this->userRepository->update($userId, $modelAttr);
        }
    }
}
