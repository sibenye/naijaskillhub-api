<?php

namespace App\Services;

use App\Repositories\UserAttributeRepository;
use App\Models\Requests\Admin\UserAttributePostRequest;
use Illuminate\Validation\ValidationException;

class UserAttributeService {

    /**
     *
     * @var UserAttributeRepository
     */
    private $repository;

    public function __construct(UserAttributeRepository $repository) {
        $this->repository = $repository;
    }

    public function get($id = NULL) {
        return $this->repository->get($id);
    }

    public function createUserAttribute(UserAttributePostRequest $request) {
        // ensure that the attribute name is not taken
        if ($this->repository->getUserAttributeByName($request->getName())) {
            throw new ValidationException(NULL,
                'The UserAttribute name is already in use.');
        }
        $modelAttributes = $request->buildModelAttributes();

        $userAttribute = $this->repository->create($modelAttributes);

        return $userAttribute;
    }

    public function updateUserAttribute(UserAttributePostRequest $request) {
        // ensure that the userAttributeId is valid
        $this->repository->get($request->getUserAttributeId());

        // ensure that the attribute name is not taken
        $existingAttributeByName = $this->repository->getUserAttributeByName(
                $request->getName());
        if ($existingAttributeByName && $request->getUserAttributeId() !=
                 $existingAttributeByName ['id']) {
            throw new ValidationException(NULL,
                'The UserAttribute name is already in use.');
        }

        $modelAttributes = $request->buildModelAttributes();

        $userAttribute = $this->repository->update(
                $request->getUserAttributeId(), $modelAttributes);

        return $userAttribute;
    }

}
