<?php
namespace App\Http\Controllers;

use App\Mappers\UserPostRequestMapper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     *
     * @var UserService
     */
    private $service;

    /**
     *
     * @var UserPostRequestMapper
     */
    private $userPostMapper;

    /**
     *
     * @param Request $request
     * @param UserService $service
     * @param UserPostRequestMapper $userPostMapper
     */
    public function __construct(Request $request, UserService $service,
            UserPostRequestMapper $userPostMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userPostMapper = $userPostMapper;
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function getUser($id)
    {
        $user = $this->service->getUser($id);

        return $this->response($user);
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function getUserAttributes($id)
    {
        $requestedAttributes = $this->request->input('attributeNames', NULL);
        $requestedAttributesArray = $requestedAttributes ? preg_split('/,/', $requestedAttributes) : [ ];
        $userAttributes = $this->service->getUserAttributes($id, $requestedAttributesArray);

        return $this->response($userAttributes);
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function getUserCategories($id)
    {
        $userCategories = $this->service->getUserCategories($id);

        return $this->response($userCategories);
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function getUserCredentialTypes($id)
    {
        $userCredentialTypes = $this->service->getUserCredentialTypes($id);

        return $this->response($userCredentialTypes);
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function upsertUserAttributeValue($id)
    {
        $requestBody = $this->request->all();

        $this->service->upsertUserAttributeValue($id, $requestBody);

        return $this->response();
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function linkUserToCategory($id)
    {
        $requestBody = $this->request->all();

        $this->service->linkUserToCategory($id, $requestBody);

        return $this->response();
    }

    /**
     *
     * @param string $id
     * @return Response
     */
    public function unlinkUserFromCategory($id)
    {
        $requestBody = $this->request->all();

        $this->service->unlinkUserFromCategory($id, $requestBody);

        return $this->response();
    }

    /**
     *
     * @return Response
     */
    public function registerUser()
    {
        $postRequest = $this->userPostMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $requestBody = $postRequest->buildModelAttributes();

        $user = $this->service->registerUser($requestBody);
        return $this->response($user);
    }
}
