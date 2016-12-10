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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Utilities\NSHCryptoUtil;

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

    public function getAllUserPortfolios($id)
    {
        $user = $this->userRepository->get($id);

        $result = array ();
        $result ['userId'] = $id;
        $result ['images'] = $this->mapImagesResponse($user);
        $result ['videos'] = $this->mapVideosResponse($user);
        $result ['voiceClips'] = $this->mapVoiceclipsResponse($user);
        $result ['credits'] = $this->mapCreditsResponse($user);

        return $result;
    }

    public function getUserImagesPortfolio($id)
    {
        $user = $this->userRepository->get($id);

        $result = array ();
        $result ['userId'] = $id;
        $result ['images'] = $this->mapImagesResponse($user);

        return $result;
    }

    public function getUserVideosPortfolio($id)
    {
        $user = $this->userRepository->get($id);

        $result = array ();
        $result ['userId'] = $id;
        $result ['videos'] = $this->mapVideosResponse($user);

        return $result;
    }

    public function getUserVoiceclipsPortfolio($id)
    {
        $user = $this->userRepository->get($id);

        $result = array ();
        $result ['userId'] = $id;
        $result ['voiceClips'] = $this->mapVoiceclipsResponse($user);

        return $result;
    }

    public function getUserCreditsPortfolio($id)
    {
        $user = $this->userRepository->get($id);

        $result = array ();
        $result ['userId'] = $id;
        $result ['credits'] = $this->mapCreditsResponse($user);

        return $result;
    }

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

    public function linkUserToCategory($userId, $categoriesRequest)
    {
        // ensure the categoryIds are valid
        foreach ($categoriesRequest as $request) {
            $this->categoryRepository->get($request ['categoryId']);
        }

        $this->userRepository->linkUserToCategory($userId, $categoriesRequest);
    }

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

    private function mapImagesResponse(Model $user)
    {
        $images = $user->images;
        $imagesContent = array ();
        foreach ($images as $key => $value) {
            $imagesContent [$key] ['imageId'] = $value->id;
            $imagesContent [$key] ['imageUrl'] = $value->imageUrl;
            $imagesContent [$key] ['caption'] = $value->caption;
            $imagesContent [$key] ['createdDate'] = $value->createdDate;
            $imagesContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }

        return $imagesContent;
    }

    private function mapVideosResponse(Model $user)
    {
        $videos = $user->videos;
        $videosContent = array ();
        foreach ($videos as $key => $value) {
            $videosContent [$key] ['videoId'] = $value->id;
            $videosContent [$key] ['videoUrl'] = $value->videoUrl;
            $videosContent [$key] ['caption'] = $value->caption;
            $videosContent [$key] ['createdDate'] = $value->createdDate;
            $videosContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }

        return $videosContent;
    }

    private function mapVoiceclipsResponse(Model $user)
    {
        $voiceClips = $user->voiceClips;
        $voiceClipsContent = array ();
        foreach ($voiceClips as $key => $value) {
            $voiceClipsContent [$key] ['clipId'] = $value->id;
            $voiceClipsContent [$key] ['clipUrl'] = $value->clipUrl;
            $voiceClipsContent [$key] ['caption'] = $value->caption;
            $voiceClipsContent [$key] ['createdDate'] = $value->createdDate;
            $voiceClipsContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }
        return $voiceClipsContent;
    }

    private function mapCreditsResponse(Model $user)
    {
        $credits = $user->credits;
        $creditsContent = array ();
        foreach ($credits as $key => $value) {
            $creditsContent [$key] ['creditId'] = $value->id;
            $creditsContent [$key] ['creditTypeName'] = $value->name;
            $creditsContent [$key] ['creditTypeId'] = $value->pivot->creditTypeId;
            $creditsContent [$key] ['year'] = $value->pivot->year;
            $creditsContent [$key] ['caption'] = $value->pivot->caption;
            $creditsContent [$key] ['createdDate'] = $value->pivot->createdDate;
            $creditsContent [$key] ['modifiedDate'] = $value->pivot->modifiedDate;
        }

        return $creditsContent;
    }
}
