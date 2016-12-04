<?php

namespace App\Http\Controllers;

use App\Mappers\UserAttributeValuePostRequestMapper;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     *
     * @var UserService
     */
    private $service;
    private $attributeValuePostMapper;

    public function __construct(Request $request, UserService $service,
            UserAttributeValuePostRequestMapper $attributeValuePostMapper) {
        parent::__construct($request);
        $this->service = $service;
        $this->attributeValuePostMapper = $attributeValuePostMapper;
    }

    public function getUser($id) {
        $user = $this->service->getUser($id);

        return $this->response($user);
    }

    public function getUserAttributes($id) {
        $requestedAttributes = $this->request->input('attributeNames', '');
        $requestedAttributesArray = preg_split('/,/', $requestedAttributes);
        $userAttributes = $this->service->getUserAttributes($id,
                $requestedAttributesArray);

        return $this->response($userAttributes);
    }

    public function getUserPortfolios($id) {
        $userPortfolios = $this->service->getUserPortfolios($id);

        return $this->response($userPortfolios);
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

        $userAttributeValues = $this->service->upsertUserAttributeValue($id,
                $requestBody);

        return $this->response($userAttributeValues);
    }

}
