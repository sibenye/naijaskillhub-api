<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Mappers\LoginRequestMapper;
use App\Mappers\RegisterRequestMapper;

/**
 * Authentication Controller.
 *
 * @author silver.ibenye
 *
 */
class AuthController extends Controller
{
    /**
     *
     * @var AuthService
     */
    private $authService;

    /**
     *
     * @var LoginRequestMapper
     */
    private $loginRequestMapper;

    /**
     *
     * @var RegisterRequestMapper
     */
    private $registerRequestMapper;

    /**
     *
     * @param Request $request
     * @param AuthService $authService
     * @param UserPostRequestMapper $registerRequestMapper
     * @param LoginRequestMapper $loginRequestMapper
     */
    public function __construct(Request $request, AuthService $authService,
            RegisterRequestMapper $registerRequestMapper, LoginRequestMapper $loginRequestMapper)
    {
        parent::__construct($request);
        $this->authService = $authService;
        $this->userPostMapper = $registerRequestMapper;
        $this->loginRequestMapper = $loginRequestMapper;
    }

    /**
     *
     * @return Response
     */
    public function login()
    {
        $request = $this->loginRequestMapper->map($this->request->all());
        $this->validateRequest($request->getValidationRules());

        $authResponse = $this->authService->login($request);

        return $this->response($authResponse);
    }

    /**
     *
     * @return Response
     */
    public function logout()
    {
        $emailAddress = $this->request->input('emailAddress', NULL);

        $this->authService->logout($emailAddress);

        return $this->response();
    }

    /**
     *
     * @return Response
     */
    public function register()
    {
        $postRequest = $this->userPostMapper->map($this->request->all());
        $this->validateRequest($postRequest->getValidationRules());

        $user = $this->authService->register($postRequest);
        return $this->response($user);
    }
}
