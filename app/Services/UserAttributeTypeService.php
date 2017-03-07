<?php
namespace App\Services;

use App\Repositories\UserAttributeTypeRepository;

class UserAttributeTypeService
{
    /**
     *
     * @var UserAttributeTypeRepository
     */
    private $userAttributeTypeRepository;

    public function __construct(UserAttributeTypeRepository $userAttributeTypeRepository)
    {
        $this->userAttributeTypeRepository = $userAttributeTypeRepository;
    }

    public function get($id = NULL)
    {
        return $this->userAttributeTypeRepository->get($id);
    }
}
