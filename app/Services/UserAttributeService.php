<?php
namespace App\Services;

use App\Repositories\UserAttributeRepository;
use App\Models\Requests\Admin\UserAttributePostRequest;
use Illuminate\Validation\ValidationException;
use App\Repositories\UserAttributeTypeRepository;

class UserAttributeService
{

    /**
     *
     * @var UserAttributeRepository
     */
    private $userAttributeRepository;

    /**
     *
     * @var UserAttributeTypeRepository
     */
    private $userAttributeTypeRepository;

    public function __construct(UserAttributeRepository $userAttributeRepository,
            UserAttributeTypeRepository $userAttributeTypeRepository)
    {
        $this->userAttributeRepository = $userAttributeRepository;
        $this->userAttributeTypeRepository = $userAttributeTypeRepository;
    }

    public function get($id = NULL, $attributeType = NULL)
    {
        if ($attributeType) {
            return $this->getUserAttributesByType($attributeType);
        }
        return $this->userAttributeRepository->get($id);
    }

    public function getUserAttributesByType($attributeType)
    {
        $attributeType = $this->userAttributeTypeRepository->getUserAttributeTypeByName(
                $attributeType, true);

        $userAttributes = $this->userAttributeRepository->getUserAttributesByType(
                $attributeType->id);

        return $userAttributes;
    }

    public function createUserAttribute(UserAttributePostRequest $request)
    {
        // ensure that the attribute name is not taken
        if ($this->userAttributeRepository->getUserAttributeByName($request->getName())) {
            throw new ValidationException(NULL, 'The UserAttribute name is already in use.');
        }

        // ensure that the attribute type Id is valid
        $this->validateAttributeTypeId($request->getUserAttributeTypeId());

        $modelAttributes = $request->buildModelAttributes();

        $userAttribute = $this->userAttributeRepository->create($modelAttributes);

        return $userAttribute;
    }

    public function updateUserAttribute(UserAttributePostRequest $request)
    {
        // ensure that the userAttributeId is valid
        $this->userAttributeRepository->get($request->getUserAttributeId());

        // ensure that the attribute type Id is valid
        $this->validateAttributeTypeId($request->getUserAttributeTypeId());

        // ensure that the attribute name is not taken
        $existingAttributeByName = $this->userAttributeRepository->getUserAttributeByName(
                $request->getName());
        if ($existingAttributeByName && $request->getUserAttributeId() !=
                 $existingAttributeByName ['id']) {
            throw new ValidationException(NULL, 'The UserAttribute name is already in use.');
        }

        $modelAttributes = $request->buildModelAttributes();

        $userAttribute = $this->userAttributeRepository->update($request->getUserAttributeId(),
                $modelAttributes);

        return $userAttribute;
    }

    private function validateAttributeTypeId($userAttributeTypeId)
    {
        $this->userAttributeTypeRepository->get($userAttributeTypeId);
    }
}
