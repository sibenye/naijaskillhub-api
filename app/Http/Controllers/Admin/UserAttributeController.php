<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserAttributeService;
use Illuminate\Http\Request;
use App\Models\Requests\Admin\UserAttributePostRequest;

class UserAttributeController extends Controller
{

    /**
     *
     * @var UserAttributeService
     */
    private $service;

    public function __construct(Request $request, UserAttributeService $service)
    {
        parent::__construct($request);
        $this->service = $service;
    }

    public function getUserAttributes()
    {
        $attributeType = $this->request->input("attributeType", NULL);
        $userAttributes = $this->service->get(NULL, $attributeType);

        return $this->response($userAttributes);
    }

    public function getUserAttribute($id)
    {
        $userAttribute = $this->service->get($id);

        return $this->response($userAttribute);
    }

    public function upsert()
    {
        $userAttributePostRequest = new UserAttributePostRequest($this->request->all());

        // validate request.
        $this->validateRequest($userAttributePostRequest->getValidationRules());

        if ($userAttributePostRequest->getUserAttributeId()) {
            $this->service->updateUserAttribute($userAttributePostRequest);

            return $this->response();
        }

        $userAttribute = $this->service->createUserAttribute($userAttributePostRequest);

        return $this->response($userAttribute);
    }
}
