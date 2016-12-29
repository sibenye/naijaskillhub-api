<?php
/**
 *
 * @package App\Services
 */
namespace App\Services;

use App\Enums\CredentialType;
use App\Models\Requests\AddCredentialRequest;
use App\Models\Requests\UserChangeEmailPostRequest;
use App\Models\Requests\UserChangePasswordPostRequest;
use App\Models\Requests\UserForgotPasswordPostRequest;
use App\Models\Requests\UserResetPasswordPostRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CredentialTypeRepository;
use App\Repositories\UserAttributeRepository;
use App\Repositories\UserRepository;
use App\Utilities\NSHCryptoUtil;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Requests\UserChangeVanityNamePostRequest;
use App\Models\DAO\User;
use App\Models\Requests\UserAddAccountTypeRequest;
use App\Repositories\AccountTypeRepository;
use App\Models\Requests\LinkOrUnlinkCategoryRequest;

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
     * @var AuthService
     */
    private $authService;

    /**
     *
     * @var AccountTypeRepository
     */
    private $accountTypeRepository;

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
            CredentialTypeRepository $credentialTypeRepository,
            AccountTypeRepository $accountTypeRepository, NSHCryptoUtil $cryptoUtil,
            AuthService $authService)
    {
        $this->userRepository = $repository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->credentialTypeRepository = $credentialTypeRepository;
        $this->accountTypeRepository = $accountTypeRepository;
        $this->cryptoUtil = $cryptoUtil;
        $this->authService = $authService;
    }

    /**
     *
     * @param integer $id
     * @return array
     */
    public function getUser($id)
    {
        $user = $this->userRepository->get($id);

        return $this->mapUsersResponse($user);
    }

    /**
     *
     * @param string $emailAddress
     * @return array
     */
    public function getUserByEmailAddress($emailAddress)
    {
        $user = $this->userRepository->getUserByEmailAddress($emailAddress);

        if (empty($user)) {
            throw new ModelNotFoundException();
        }

        return $this->mapUsersResponse($user);
    }

    /**
     *
     * @param string $authToken
     * @return array
     */
    public function getUserByAuthToken($authToken)
    {
        $user = $this->userRepository->getUserWhere('authToken', $authToken);

        if (empty($user)) {
            throw new ModelNotFoundException();
        }

        return $this->mapUsersResponse($user);
    }

    /**
     *
     * @param string $vanityName
     * @return array
     */
    public function getUserByVanityName($vanityName)
    {
        $user = $this->userRepository->getUserWhere('vanityName', $vanityName);

        if (empty($user)) {
            throw new ModelNotFoundException();
        }

        return $this->mapUsersResponse($user);
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
    public function linkUserToCategory($userId, LinkOrUnlinkCategoryRequest $categoriesRequest)
    {
        // ensure the categoryIds are valid
        foreach ($categoriesRequest->getCategoryIds() as $categoryId) {
            $this->categoryRepository->get($categoryId);
        }

        $this->userRepository->linkUserToCategory($userId, $categoriesRequest->getCategoryIds());
    }

    /**
     *
     * @param integer $userId
     * @param array $categoriesRequest
     * @return void
     */
    public function unlinkUserFromCategory($userId, LinkOrUnlinkCategoryRequest $categoriesRequest)
    {
        // ensure the categoryIds are valid
        foreach ($categoriesRequest->getCategoryIds() as $categoryId) {
            $this->categoryRepository->get($categoryId);
        }

        $this->userRepository->unlinkUserFromCategory($userId, $categoriesRequest->getCategoryIds());
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
        $existingUserCredentials = $this->userRepository->getUserCredentials($userId,
                CredentialType::STANDARD);

        if (count($existingUserCredentials) == 0) {
            throw new ValidationException(null, 'User does not have standard credential.');
        }

        // verify old password
        if (!$this->cryptoUtil->hashMatches($request->getOldPassword(),
                $existingUserCredentials [0]->pivot->password)) {
            throw new ValidationException(null, 'Invalid old password');
        }

        $newPasswordHash = $this->cryptoUtil->hashThis($request->getNewPassword());

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName(
                CredentialType::STANDARD)->id;

        $userCred = array ();
        $userCred ['credentialType'] = CredentialType::STANDARD;
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
    public function resetUserPassword(UserResetPasswordPostRequest $request)
    {
        // verify user
        $user = $this->userRepository->getUserByEmailAddress($request->getEmailAddress());

        if (empty($user)) {
            throw new ValidationException(null, 'Invalid EmailAddress');
        }

        $userId = $user->id;

        // password reset is only for standard credentials.
        $existingUserCredentials = $this->userRepository->getUserCredentials($userId,
                CredentialType::STANDARD);

        if (count($existingUserCredentials) == 0) {
            throw new ValidationException(null, 'User does not have standard credential.');
        }

        // validate reset token
        if ($user->resetToken != $request->getResetToken()) {
            throw new ValidationException(null, 'resetToken is invalid.');
        }

        $newPasswordHash = $this->cryptoUtil->hashThis($request->getNewPassword());

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName('standard')->id;

        $userCred = array ();
        $userCred ['credentialType'] = CredentialType::STANDARD;
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
    public function forgotUserPassword(UserForgotPasswordPostRequest $request)
    {
        // verify user
        $user = $this->userRepository->getUserByEmailAddress($request->getEmailAddress());

        if (empty($user)) {
            throw new ValidationException(null, 'Invalid EmailAddress');
        }

        $userId = $user->id;

        // forgot password is only for standard credentials.
        $existingUserCredentials = $this->userRepository->getUserCredentials($userId,
                CredentialType::STANDARD);

        if (count($existingUserCredentials) == 0) {
            throw new ValidationException(null, 'User does not have standard credential.');
        }

        $resetToken = $request->getResetToken();

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

        // ensure emailAddress is not already in use
        $existingUserWithEmailAddress = $this->userRepository->getUserByEmailAddress(
                $request->getNewEmailAddress());

        if (!empty($existingUserWithEmailAddress) && ($existingUserWithEmailAddress->id != $user->id)) {
            throw new ValidationException(null, 'EmailAddress is already in use');
        }

        if (strtolower($user->emailAddress) != strtolower($request->getNewEmailAddress())) {
            $modelAttr = [
                    'emailAddress' => $request->getNewEmailAddress(),
                    'isActive' => false
            ];
            $this->userRepository->update($userId, $modelAttr);
        }
    }

    /**
     *
     * @param integer $userId
     * @param UserChangeVanityNamePostRequest $request
     * @return void
     */
    public function changeUserVanityName($userId, UserChangeVanityNamePostRequest $request)
    {

        // verify user
        $user = $this->userRepository->get($userId);

        // ensure vanityName is not already in use
        $existingUserWithVanityName = $this->userRepository->getUserWhere('vanityName',
                $request->getNewVanityName());

        if (!empty($existingUserWithVanityName) && ($existingUserWithVanityName->id != $user->id)) {
            throw new ValidationException(null, 'VanityName is already in use');
        }

        if (strtolower($user->vanityName) != strtolower($request->getNewVanityName())) {
            $modelAttr = [
                    'vanityName' => $request->getNewVanityName()
            ];
            $this->userRepository->update($userId, $modelAttr);
        }
    }

    /**
     *
     * @param integer $userId
     * @param AddCredentialRequest $request
     * @return void
     * @throws ValidationException
     */
    public function addStandardCredential($userId, AddCredentialRequest $request)
    {
        // verify user
        $user = $this->userRepository->get($userId);

        if (strtolower($user->emailAddress) != strtolower($request->getEmailAddress())) {
            throw new ValidationException(null, 'EmailAddress does not belong to user');
        }

        if ($request->getCredntialType() != CredentialType::STANDARD) {
            throw new ValidationException(null, 'Invalid CredentialType. Expecting standard');
        }

        $passwordHash = $this->cryptoUtil->hashThis($request->getPassword());

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName(
                CredentialType::STANDARD)->id;

        $userCred = array ();
        $userCred ['credentialType'] = CredentialType::STANDARD;
        $userCred ['credentialTypeId'] = $credentialTypeId;
        $userCred ['password'] = $passwordHash;

        $this->userRepository->upsertUserCredential($user, $userCred);
    }

    /**
     *
     * @param AddCredentialRequest $request
     * @return void
     * @throws ValidationException
     */
    public function addSocialCredential(AddCredentialRequest $request)
    {
        // verify user email
        $user = $this->userRepository->getUserByEmailAddress($request->getEmailAddress());

        if (empty($user)) {
            throw new ValidationException(null, 'Invalid EmailAddress');
        }

        // verify cred type
        if ($request->getCredntialType() != CredentialType::FACEBOOK &&
                 $request->getCredntialType() != CredentialType::GOOGLE) {
            throw new ValidationException(null,
                'Invalid CredentialType. Expecting either google or facebook');
        }

        $credentialTypeId = $this->credentialTypeRepository->getCredentialTypeByName(
                $request->getCredntialType())->id;

        $userCred = array ();
        $userCred ['credentialType'] = $request->getCredntialType();
        $userCred ['credentialTypeId'] = $credentialTypeId;
        $userCred ['password'] = $request->getPassword();

        $this->userRepository->upsertUserCredential($user, $userCred);
    }

    /**
     *
     * @param integer $userId
     * @param UserAddAccountTypeRequest $request
     * @throws ValidationException
     * @return void
     */
    public function addAccountType($userId, UserAddAccountTypeRequest $request)
    {
        // verify user
        $user = $this->userRepository->get($userId);

        // verify account type
        $accountType = $this->accountTypeRepository->getAccountTypeByName(
                $request->getAccountType());
        if (empty($accountType)) {
            throw new ValidationException(NULL, 'The accountType is invalid.');
        }

        $this->userRepository->addAccountType($user, $accountType);
    }

    /**
     *
     * @param User $user
     * @return array
     */
    private function mapUsersResponse(User $user)
    {
        $userCredentialTypes = $user->credentialTypes;

        $userCredentialTypesContent = array ();

        foreach ($userCredentialTypes as $key => $value) {
            $userCredentialTypesContent [$key] = $value->name;
        }

        $userAccountTypes = $user->accountTypes;

        $userAccountTypesContent = array ();

        foreach ($userAccountTypes as $key => $value) {
            $userAccountTypesContent [$key] = $value->name;
        }

        $userAttributes = $user->userAttributes;

        $userAttributesContent = array ();
        $i = 0;

        foreach ($userAttributes as $value) {
            $userAttributesContent [$i] ['attributeId'] = $value->id;
            $userAttributesContent [$i] ['attributeName'] = $value->name;
            $userAttributesContent [$i] ['attributeValue'] = $value->pivot->attributeValue;

            $i++;
        }

        $userCategories = $user->categories;

        $userCategoriesContent = array ();

        foreach ($userCategories as $key => $value) {
            $userCategoriesContent [$key] ['categoryId'] = $value->id;
            $userCategoriesContent [$key] ['categoryName'] = $value->name;
        }

        $userContent = array ();
        $userContent ['userId'] = $user->id;
        $userContent ['isActive'] = $user->isActive;
        $userContent ['emailAddress'] = $user->emailAddress;
        $userContent ['vanityName'] = $user->vanityName;
        $userContent ['credentialTypes'] = $userCredentialTypesContent;
        $userContent ['accountTypes'] = $userAccountTypesContent;
        $userContent ['attributes'] = $userAttributesContent;
        $userContent ['categories'] = $userCategoriesContent;
        $userContent ['createdDate'] = $user->createdDate->toDateTimeString();
        $userContent ['modifiedDate'] = $user->modifiedDate->toDateTimeString();

        return $userContent;
    }
}
