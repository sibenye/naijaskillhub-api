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
     * @param string $id
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
     * @param string $userId
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
     * @param string $id
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
     * @param string $id
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
     * @param string $userId
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
     * @param string $userId
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
     * @param string $userId
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
     * @param array $requestBody
     * @throws ValidationException
     * @return array Associative array.
     */
    public function registerUser($requestBody)
    {
        // ensure emailAddress is not already in use
        if ($this->userRepository->getUserByEmailAddress($requestBody ['emailAddress'])) {
            throw new ValidationException(NULL, 'The emailAddress is already in use.');
        }

        // ensure credentialType is valid
        $credentialType = $this->credentialTypeRepository->getCredentialTypeByName(
                $requestBody ['credentialType']);
        if (empty($credentialType)) {
            throw new ValidationException(NULL, 'The credentialType is invalid.');
        }

        if ($requestBody ['credentialType'] == CredentialType::STANDARD) {
            $requestBody ['password'] = $this->cryptoUtil->hashThis($requestBody ['password']);
        }

        $requestBody ['credentialTypeId'] = $credentialType->id;

        $user = $this->userRepository->createUser($requestBody);

        return $user;
    }
}
