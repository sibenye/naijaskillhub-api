<?php
namespace App\Http\Controllers;

use App\Mappers\AddCredentialRequestMapper;
use App\Mappers\LinkOrUnlinkCategoryRequestMapper;
use App\Mappers\UserAddAccountTypeRequestMapper;
use App\Mappers\UserChangeEmailPostRequestMapper;
use App\Mappers\UserChangePasswordPostRequestMapper;
use App\Mappers\UserChangeVanityNamePostRequestMapper;
use App\Mappers\UserForgotPasswordPostRequestMapper;
use App\Mappers\UserResetPasswordPostRequestMapper;
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
     * @var UserChangeVanityNamePostRequestMapper
     */
    private $userChangeVanityNamePostRequestMapper;

    /**
     *
     * @var AddCredentialRequestMapper
     */
    private $addCredentialRequestMapper;

    /**
     *
     * @var UserAddAccountTypeRequestMappers
     */
    private $userAddAccoutTypeRequestMapper;

    /**
     *
     * @var LinkOrUnlinkCategoryRequestMapper
     */
    private $linkOrUnlinkCategoryRequestMapper;

    /**
     *
     * @var UserProfileImagePostRequestMapper
     */
    private $userProfileImagePostRequestMapper;

    /**
     *
     * @param Request                               $request
     * @param UserService                           $service
     * @param UserPostRequestMapper                 $userPostMapper
     * @param UserChangePasswordPostRequestMapper   $userChangePasswordPostRequestMapper
     * @param UserResetPasswordPostRequestMapper    $userResetPasswordPostRequestMapper
     * @param UserChangeEmailPostRequestMapper      $userChangeEmailPostRequestMapper
     * @param UserChangeVanityNamePostRequestMapper $userChangeVanityNamePostRequestMapper
     * @param AddCredentialRequestMapper            $addCredentialRequestMapper
     * @param UserAddAccountTypeRequestMapper       $userAddAccoutTypeRequestMapper
     */
    public function __construct(Request $request, UserService $service,
            UserChangePasswordPostRequestMapper $userChangePasswordPostRequestMapper,
            UserResetPasswordPostRequestMapper $userResetPasswordPostRequestMapper,
            UserForgotPasswordPostRequestMapper $userForgotPasswordPostRequestMapper,
            UserChangeEmailPostRequestMapper $userChangeEmailPostRequestMapper,
            UserChangeVanityNamePostRequestMapper $userChangeVanityNamePostRequestMapper,
            AddCredentialRequestMapper $addCredentialRequestMapper,
            UserAddAccountTypeRequestMapper $userAddAccoutTypeRequestMapper,
            LinkOrUnlinkCategoryRequestMapper $linkOrUnlinkCategoryRequestMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userChangePasswordPostRequestMapper = $userChangePasswordPostRequestMapper;
        $this->userResetPasswordPostRequestMapper = $userResetPasswordPostRequestMapper;
        $this->userForgotPasswordPostRequestMapper = $userForgotPasswordPostRequestMapper;
        $this->userChangeEmailPostRequestMapper = $userChangeEmailPostRequestMapper;
        $this->userChangeVanityNamePostRequestMapper = $userChangeVanityNamePostRequestMapper;
        $this->addCredentialRequestMapper = $addCredentialRequestMapper;
        $this->userAddAccoutTypeRequestMapper = $userAddAccoutTypeRequestMapper;
        $this->linkOrUnlinkCategoryRequestMapper = $linkOrUnlinkCategoryRequestMapper;
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
     * @param string $email EmailAddress.
     * @return Response
     */
    public function getUserByEmailAddress($email)
    {
        $user = $this->service->getUserByEmailAddress($email);

        return $this->response($user);
    }

    /**
     *
     * @param string $authToken Auth Token.
     * @return Response
     */
    public function getUserByAuthToken($authToken)
    {
        $user = $this->service->getUserByAuthToken($authToken);

        return $this->response($user);
    }

    /**
     *
     * @param string $vanityName Vanity name.
     * @return Response
     */
    public function getUserByVanityName($vanityName)
    {
        $user = $this->service->getUserByVanityName($vanityName);

        return $this->response($user);
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function getUserAttributeValues($id)
    {
        $requestedAttributeType = $this->request->input('attributeType', NULL);
        $requestedAttributes = $this->request->input('attributeNames', NULL);
        $requestedAttributesArray = $requestedAttributes ? preg_split('/,/', $requestedAttributes) : [ ];
        $userAttributes = $this->service->getUserAttributes($id, $requestedAttributesArray,
                $requestedAttributeType);

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
        $postRequest = $this->linkOrUnlinkCategoryRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->linkUserToCategory($id, $postRequest);

        return $this->response();
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function unlinkUserFromCategory($id)
    {
        $postRequest = $this->linkOrUnlinkCategoryRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->unlinkUserFromCategory($id, $postRequest);

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

        $response = $this->service->changeUserEmailAddress($id, $postRequest);
        return $this->response($response);
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function changeUserVanityName($id)
    {
        $postRequest = $this->userChangeVanityNamePostRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->changeUserVanityName($id, $postRequest);
        return $this->response();
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function addStandardCredential($id)
    {
        $postRequest = $this->addCredentialRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->addStandardCredential($id, $postRequest);
        return $this->response();
    }

    /**
     *
     * @return Response
     */
    public function addSocialCredential()
    {
        $postRequest = $this->addCredentialRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->addSocialCredential($postRequest);
        return $this->response();
    }

    /**
     *
     * @param integer $id User Id.
     * @return Response
     */
    public function addAccountType($id)
    {
        $postRequest = $this->userAddAccoutTypeRequestMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $this->service->addAccountType($id, $postRequest);
        return $this->response();
    }
}
