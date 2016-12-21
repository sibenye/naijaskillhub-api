<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Mappers\LoginRequestMapper;

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
     * @param Request $request
     * @param AuthService $authService
     */
    public function __construct(Request $request, AuthService $authService,
            LoginRequestMapper $loginRequestMapper)
    {
        parent::__construct($request);
        $this->authService = $authService;
        $this->loginRequestMapper = $loginRequestMapper;
    }

    public function login()
    {
        $request = $this->loginRequestMapper->map($this->request->all());
        $this->validateRequest($request->getValidationRules());

        $authResponse = $this->authService->login($request);

        return $this->response($authResponse);
    }

    public function logout()
    {
        $emailAddress = $this->request->input('emailAddress', NULL);

        $this->authService->logout($emailAddress);

        return $this->response();
    }
}
