<?php
namespace App\Http\Controllers;

use App\Mappers\UserPostRequestMapper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mappers\UserChangePasswordPostRequestMapper;
use App\Mappers\UserResetPasswordPostRequestMapper;

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
     * @var UserChangePasswordPostRequestMapper
     */
    private $userChangePasswordPostRequestMapper;

    /**
     *
     * @var UserResetPasswordPostRequestMapper
     */
    private $userResetPasswordPostRequestMapper;

    /**
     *
     * @param Request                             $request
     * @param UserService                         $service
     * @param UserPostRequestMapper               $userPostMapper
     * @param UserChangePasswordPostRequestMapper $userChangePasswordPostRequestMapper
     * @param UserResetPasswordPostRequestMapper  $userResetPasswordPostRequestMapper
     */
    public function __construct(Request $request, UserService $service,
            UserPostRequestMapper $userPostMapper,
            UserChangePasswordPostRequestMapper $userChangePasswordPostRequestMapper,
            UserResetPasswordPostRequestMapper $userResetPasswordPostRequestMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userPostMapper = $userPostMapper;
        $this->userChangePasswordPostRequestMapper = $userChangePasswordPostRequestMapper;
        $this->userResetPasswordPostRequestMapper = $userResetPasswordPostRequestMapper;
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function getUser($id)
    {
        $user = $this->service->getUser($id);

        return $this->response($user);
    }

    /**
     *
     * @param integer $id User Id.
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
     * @param integer $id User Id.
     * @return Response
     */
    public function getUserCategories($id)
    {
        $userCategories = $this->service->getUserCategories($id);

        return $this->response($userCategories);
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function getUserCredentialTypes($id)
    {
        $userCredentialTypes = $this->service->getUserCredentialTypes($id);

        return $this->response($userCredentialTypes);
    }

    /**
     *
     * @param integer $id User Id.
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
     * @param integer $id User Id.
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
     * @param integer $id User Id.
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

        $user = $this->service->registerUser($postRequest);
        return $this->response($user);
    }

    /**
     * @param integer $id User Id.
     * @return Response
     */
    public function changeUserPassword($id)
    {
        $postRequest = $this->userChangePasswordPostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->changeUserPassword($id, $postRequest);
        return $this->response();
    }

    /**
     * @param integer $id User Id.
     * @return Response
     */
    public function resetUserPassword($id)
    {
        $postRequest = $this->userResetPasswordPostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->resetUserPassword($id, $postRequest);
        return $this->response();
    }

    /**
     * @param integer $id User Id.
     * @return Response
     */
    public function resetRequest($id)
    {
        $resetToken = $this->request->input('resetToken', NULL);

        $this->service->insertResetToken($id, $resetToken);
        return $this->response();
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function activateUser($id)
    {
        $this->service->activateUser($id);
        return $this->response();
    }
}
