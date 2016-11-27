<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {
    /**
     *
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function getUser($id) {
        $user = $this->repository->get($id);

        return $user;
    }

}