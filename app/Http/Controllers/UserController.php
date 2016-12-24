<?php
namespace App\Http\Controllers;

use App\Mappers\UserPostRequestMapper;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mappers\UserChangePasswordPostRequestMapper;
use App\Mappers\UserResetPasswordPostRequestMapper;
use App\Mappers\UserChangeEmailPostRequestMapper;
use App\Mappers\UserForgotPasswordPostRequestMapper;

class UserController extends Controller
{
    /**
     *
     * @var UserService
     */
    private $service;

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
     * @var UserForgotPasswordPostRequestMapper
     */
    private $userForgotPasswordPostRequestMapper;

    /**
     *
     * @var UserChangeEmailPostRequestMapper
     */
    private $userChangeEmailPostRequestMapper;

    /**
     *
     * @param Request                             $request
     * @param UserService                         $service
     * @param UserPostRequestMapper               $userPostMapper
     * @param UserChangePasswordPostRequestMapper $userChangePasswordPostRequestMapper
     * @param UserResetPasswordPostRequestMapper  $userResetPasswordPostRequestMapper
     * @param UserChangeEmailPostRequestMapper    $userChangeEmailPostRequestMapper
     */
    public function __construct(Request $request, UserService $service,
            UserChangePasswordPostRequestMapper $userChangePasswordPostRequestMapper,
            UserResetPasswordPostRequestMapper $userResetPasswordPostRequestMapper,
            UserForgotPasswordPostRequestMapper $userForgotPasswordPostRequestMapper,
            UserChangeEmailPostRequestMapper $userChangeEmailPostRequestMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userChangePasswordPostRequestMapper = $userChangePasswordPostRequestMapper;
        $this->userResetPasswordPostRequestMapper = $userResetPasswordPostRequestMapper;
        $this->userForgotPasswordPostRequestMapper = $userForgotPasswordPostRequestMapper;
        $this->userChangeEmailPostRequestMapper = $userChangeEmailPostRequestMapper;
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
     * During a forgot password scenario, after the user has click on the link in the reset password email,
     * The UI then makes the user to put in a new password and then calls this endpoint.
     * This endpoint resets the user's password to the specified new password.
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function resetUserPassword()
    {
        $postRequest = $this->userResetPasswordPostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->resetUserPassword($postRequest);
        return $this->response();
    }

    /**
     * This endpoint is called during a 'forgot password' scenario.
     * The UI makes the user put in their emailAddress,
     * and then it generates a token and calls this endpoint.
     * This endpoint associates the token with the user.
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function forgotPassword()
    {
        $postRequest = $this->userForgotPasswordPostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->forgotUserPassword($postRequest);
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

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function changeUserEmailAddress($id)
    {
        $postRequest = $this->userChangeEmailPostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->changeUserEmailAddress($id, $postRequest);
        return $this->response();
    }
}
