<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserAttributeTypeService;
use Illuminate\Http\Request;

class UserAttributeTypeController extends Controller
{

    /**
     *
     * @var UserAttributeTypeService
     */
    private $service;

    public function __construct(Request $request, UserAttributeTypeService $service)
    {
        parent::__construct($request);
        $this->service = $service;
    }

    public function getUserAttributeTypes()
    {
        $userAttributes = $this->service->get();

        return $this->response($userAttributes);
    }

    public function getUserAttributeType($id)
    {
        $userAttribute = $this->service->get($id);

        return $this->response($userAttribute);
    }
}
