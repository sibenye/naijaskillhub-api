<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\Requests\UserAttributeValuePostRequest;
use App\Repositories\UserAttributeRepository;

class UserService {
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

    public function __construct(UserRepository $repository,
            UserAttributeRepository $userAttributeRepository) {
        $this->userRepository = $repository;
        $this->userAttributeRepository = $userAttributeRepository;
    }

    public function getUser($id) {
        $user = $this->userRepository->get($id);

        $userContent = array ();
        $userContent ['userId'] = $user->id;
        $userContent ['isActive'] = $user->isActive;
        $userContent ['emailAddress'] = $user->emailAddress;
        $userContent ['createdDate'] = $user->createdDate->toDateTimeString();
        $userContent ['modifiedDate'] = $user->modifiedDate->toDateTimeString();

        return $userContent;
    }

    public function getUserAttributes($userId, $requestedAttributes = []) {
        $userAttributes = $this->userRepository->getUserAttributes($userId,
                $requestedAttributes);

        $userAttributesContent = array ();

        foreach ($userAttributes as $key => $value) {
            $userAttributesContent [$key] ['attributeId'] = $value->id;
            $userAttributesContent [$key] ['attributeName'] = $value->name;
            $userAttributesContent [$key] ['attributeValue'] = $value->pivot->attributeValue;
            $userAttributesContent [$key] ['createdDate'] = $value->pivot->createdDate;
            $userAttributesContent [$key] ['modifiedDate'] = $value->pivot->modifiedDate;
        }

        $result = array ();
        $result ['userId'] = $userId;
        $result ['attributes'] = $userAttributesContent;

        return $result;
    }

    public function getUserPortfolios($id) {
        $user = $this->userRepository->get($id);

        $images = $user->images;
        $videos = $user->videos;
        $voiceClips = $user->voiceClips;
        $credits = $user->credits;

        $imagesContent = array ();
        foreach ($images as $key => $value) {
            $imagesContent [$key] ['imageId'] = $value->id;
            $imagesContent [$key] ['imageUrl'] = $value->imageUrl;
            $imagesContent [$key] ['caption'] = $value->caption;
            $imagesContent [$key] ['createdDate'] = $value->createdDate;
            $imagesContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }

        $videosContent = array ();
        foreach ($videos as $key => $value) {
            $videosContent [$key] ['videoId'] = $value->id;
            $videosContent [$key] ['videoUrl'] = $value->videoUrl;
            $videosContent [$key] ['caption'] = $value->caption;
            $videosContent [$key] ['createdDate'] = $value->createdDate;
            $videosContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }

        $voiceClipsContent = array ();
        foreach ($voiceClips as $key => $value) {
            $voiceClipsContent [$key] ['clipId'] = $value->id;
            $voiceClipsContent [$key] ['clipUrl'] = $value->clipUrl;
            $voiceClipsContent [$key] ['caption'] = $value->caption;
            $voiceClipsContent [$key] ['createdDate'] = $value->createdDate;
            $voiceClipsContent [$key] ['modifiedDate'] = $value->modifiedDate;
        }

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

        $result = array ();
        $result ['userId'] = $id;
        $result ['images'] = $imagesContent;
        $result ['videos'] = $videosContent;
        $result ['voiceClips'] = $voiceClips;
        $result ['credits'] = $creditsContent;

        return $result;
    }

    public function getUserCategories($id) {
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

    public function getUserCredentialTypes($id) {
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

    public function upsertUserAttributeValue($userId,
            $userAttributeValuePostRequest) {
        // validate user Id
        $user = $this->userRepository->get($userId);

        // validate the attribute names in the request
        $attributesCollection = array ();
        $i = 0;

        foreach ($userAttributeValuePostRequest as $attributeName => $attributeValue) {
            $i++;
            $userAttribute = $this->userAttributeRepository->getUserAttributeByName(
                    $attributeName, true);
            $attributesCollection [$i] ['attributeId'] = $userAttribute ['id'];
            $attributesCollection [$i] ['attributeValue'] = $attributeValue;
        }

        $this->userRepository->upsertUserAttributeValue($user,
                $attributesCollection);

        $attributeNames = implode(',',
                array_keys($userAttributeValuePostRequest));

        return $this->getUserAttributes($userId, $attributeNames);
    }

}
