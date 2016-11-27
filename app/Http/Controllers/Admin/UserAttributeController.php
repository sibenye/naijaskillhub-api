<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserAttributeService;
use Illuminate\Http\Request;
use App\Models\Requests\UserAttributePostRequest;

class UserAttributeController extends Controller {

    /**
     *
     * @var UserAttributeService
     */
    private $service;

    public function __construct(Request $request, UserAttributeService $service) {
        parent::__construct($request);
        $this->service = $service;
    }

    public function getUserAttributes() {
        $userAttributes = $this->service->get();

        return $this->response($userAttributes);
    }

    public function getUserAttribute($id) {
        $userAttribute = $this->service->get($id);

        return $this->response($userAttribute);
    }

    public function create() {
        $userAttributePostRequest = new UserAttributePostRequest(
            $this->request->all());

        // validate request.
        $this->validateRequest($userAttributePostRequest->getValidationRules());

        $userAttribute = $this->service->createUserAttribute(
                $userAttributePostRequest);

        return $this->response($userAttribute);
    }

    public function update($id) {
        $userAttributePostRequest = new UserAttributePostRequest(
            $this->request->all());

        $userAttributePostRequest->setUserAttributeId($id);

        $this->service->updateUserAttribute($userAttributePostRequest);

        return $this->response();
    }

}