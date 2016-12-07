<?php

namespace App\Http\Controllers;

use App\Mappers\UserPostRequestMapper;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     *
     * @var UserService
     */
    private $service;
    private $userPostMapper;

    public function __construct(Request $request, UserService $service,
            UserPostRequestMapper $userPostMapper) {
        parent::__construct($request);
        $this->service = $service;
        $this->userPostMapper = $userPostMapper;
    }

    public function getUser($id) {
        $user = $this->service->getUser($id);

        return $this->response($user);
    }

    public function getUserAttributes($id) {
        $requestedAttributes = $this->request->input('attributeNames', NULL);
        $requestedAttributesArray = $requestedAttributes ? preg_split('/,/',
                $requestedAttributes) : [ ];
        $userAttributes = $this->service->getUserAttributes($id,
                $requestedAttributesArray);

        return $this->response($userAttributes);
    }

    public function getAllUserPortfolios($id) {
        $userPortfolios = $this->service->getAllUserPortfolios($id);

        return $this->response($userPortfolios);
    }

    public function getUserImagesPortfolio($id) {
        $userImagesPortfolio = $this->service->getUserImagesPortfolio($id);

        return $this->response($userImagesPortfolio);
    }

    public function getUserVideosPortfolio($id) {
        $userVideosPortfolio = $this->service->getUserVideosPortfolio($id);

        return $this->response($userVideosPortfolio);
    }

    public function getUserVoiceclipsPortfolio($id) {
        $userVoiceclipsPortfolio = $this->service->getUserVoiceclipsPortfolio(
                $id);

        return $this->response($userVoiceclipsPortfolio);
    }

    public function getUserCreditsPortfolio($id) {
        $userCreditsPortfolio = $this->service->getUserCreditsPortfolio($id);

        return $this->response($userCreditsPortfolio);
    }

    public function getUserCategories($id) {
        $userCategories = $this->service->getUserCategories($id);

        return $this->response($userCategories);
    }

    public function getUserCredentialTypes($id) {
        $userCredentialTypes = $this->service->getUserCredentialTypes($id);

        return $this->response($userCredentialTypes);
    }

    public function upsertUserAttributeValue($id) {
        $requestBody = $this->request->all();

        $this->service->upsertUserAttributeValue($id, $requestBody);

        return $this->response();
    }

    public function linkUserToCategory($id) {
        $requestBody = $this->request->all();

        $this->service->linkUserToCategory($id, $requestBody);

        return $this->response();
    }

    public function unlinkUserFromCategory($id) {
        $requestBody = $this->request->all();

        $this->service->unlinkUserFromCategory($id, $requestBody);

        return $this->response();
    }

    public function registerUser() {
        $postRequest = $this->userPostMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $requestBody = $postRequest->buildModelAttributes();

        $user = $this->service->registerUser($requestBody);
        return $this->response($user);
    }

}
