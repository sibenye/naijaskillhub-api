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

}