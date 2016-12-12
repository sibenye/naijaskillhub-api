<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Services\UserPortfolioService;
use App\Mappers\UserImagePortfolioPostRequestMapper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mappers\UserVideoPortfolioPostRequestMapper;

/**
 * UserPortfolio Controller.
 *
 * @author silver.ibenye
 *
 */
class UserPortfolioController extends Controller
{
    /**
     *
     * @var UserPortfolioService
     */
    private $service;

    /**
     *
     * @var UserImagePortfolioPostRequestMapper
     */
    private $userImagePortfolioPostRequestMapper;

    /**
     *
     * @var UserVideoPortfolioPostRequestMapper
     */
    private $userVideoPortfolioPostRequestMapper;

    /**
     *
     * @param Request $request
     * @param UserPortfolioService $service
     * @param UserImagePortfolioPostRequestMapper $userImagePortfolioPostRequestMapper
     */
    public function __construct(Request $request, UserPortfolioService $service,
            UserImagePortfolioPostRequestMapper $userImagePortfolioPostRequestMapper,
            UserVideoPortfolioPostRequestMapper $userVideoPortfolioPostRequestMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userImagePortfolioPostRequestMapper = $userImagePortfolioPostRequestMapper;
        $this->userVideoPortfolioPostRequestMapper = $userVideoPortfolioPostRequestMapper;
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function getAllUserPortfolios($userId)
    {
        $userPortfolios = $this->service->getAllUserPortfolios($userId);

        return $this->response($userPortfolios);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function getUserImagesPortfolio($userId)
    {
        $userImagesPortfolio = $this->service->getUserImagesPortfolio($userId);

        return $this->response($userImagesPortfolio);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function getUserVideosPortfolio($userId)
    {
        $userVideosPortfolio = $this->service->getUserVideosPortfolio($userId);

        return $this->response($userVideosPortfolio);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function getUserVoiceclipsPortfolio($userId)
    {
        $userVoiceclipsPortfolio = $this->service->getUserVoiceclipsPortfolio($userId);

        return $this->response($userVoiceclipsPortfolio);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function getUserCreditsPortfolio($userId)
    {
        $userCreditsPortfolio = $this->service->getUserCreditsPortfolio($userId);

        return $this->response($userCreditsPortfolio);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function upsertUserImagePortfolio($userId)
    {
        $postRequest = $this->userImagePortfolioPostRequestMapper->map($this->request->all());
        $requestBody = $postRequest->buildModelAttributes();
        $imageId = $this->request->input("imageId", NULL);
        $userImagesPortfolio = array ();

        if (empty($imageId)) {
            $userImagesPortfolio = $this->service->createUserImagePortfolio($userId, $requestBody);
        } else {
            $userImagesPortfolio = $this->service->updateUserImagePortfolio($userId, $requestBody);
        }
        return $this->response($userImagesPortfolio);
    }

    /**
     *
     * @param string $userId
     * @return Response
     */
    public function upsertUserVideoPortfolio($userId)
    {
        $postRequest = $this->userVideoPortfolioPostRequestMapper->map($this->request->all());
        $requestBody = $postRequest->buildModelAttributes();
        $videoId = $this->request->input("videoId", NULL);
        $userVideosPortfolio = array ();

        if (empty($videoId)) {
            $userVideosPortfolio = $this->service->createUserVideoPortfolio($userId, $requestBody);
        } else {
            $userVideosPortfolio = $this->service->updateUserVideoPortfolio($userId, $requestBody);
        }
        return $this->response($userVideosPortfolio);
    }
}
