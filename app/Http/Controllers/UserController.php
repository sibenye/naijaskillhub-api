<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     *
     * @var UserService
     */
    private $service;

    public function __construct(Request $request, UserService $service) {
        parent::__construct($request);
        $this->service = $service;
    }

    public function getUser($id) {
        $user = $this->service->getUser($id);

        return $this->response($user);
    }

    public function getUserAttributes($id) {
        $userAttributes = $this->service->getUserAttributes($id);

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

}
