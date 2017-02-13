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
use App\Mappers\UserAudioPortfolioPostRequestMapper;
use App\Mappers\UserCreditPortfolioPostRequestMapper;

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
     * @var UserAudioPortfolioPostRequestMapper
     */
    private $userAudioPortfolioPostRequestMapper;

    /**
     *
     * @var UserCreditPortfolioPostRequestMapper
     */
    private $userCreditPortfolioPostRequestMapper;

    /**
     *
     * @param Request $request
     * @param UserPortfolioService $service
     * @param UserImagePortfolioPostRequestMapper $userImagePortfolioPostRequestMapper
     * @param UserVideoPortfolioPostRequestMapper $userVideoPortfolioPostRequestMapper
     * @param UserAudioPortfolioPostRequestMapper $userAudioPortfolioPostRequestMapper
     * @param UserCreditPortfolioPostRequestMapper $userCreditPortfolioPostRequestMapper
     */
    public function __construct(Request $request, UserPortfolioService $service,
            UserImagePortfolioPostRequestMapper $userImagePortfolioPostRequestMapper,
            UserVideoPortfolioPostRequestMapper $userVideoPortfolioPostRequestMapper,
            UserAudioPortfolioPostRequestMapper $userAudioPortfolioPostRequestMapper,
            UserCreditPortfolioPostRequestMapper $userCreditPortfolioPostRequestMapper)
    {
        parent::__construct($request);
        $this->service = $service;
        $this->userImagePortfolioPostRequestMapper = $userImagePortfolioPostRequestMapper;
        $this->userVideoPortfolioPostRequestMapper = $userVideoPortfolioPostRequestMapper;
        $this->userAudioPortfolioPostRequestMapper = $userAudioPortfolioPostRequestMapper;
        $this->userCreditPortfolioPostRequestMapper = $userCreditPortfolioPostRequestMapper;
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function getAllUserPortfolios($userId)
    {
        $userPortfolios = $this->service->getAllUserPortfolios($userId);

        return $this->response($userPortfolios);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function getUserImagesPortfolio($userId)
    {
        $userImagesPortfolio = $this->service->getUserImagesPortfolio($userId);

        return $this->response($userImagesPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function getUserVideosPortfolio($userId)
    {
        $userVideosPortfolio = $this->service->getUserVideosPortfolio($userId);

        return $this->response($userVideosPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function getUserAudiosPortfolio($userId)
    {
        $userVoiceclipsPortfolio = $this->service->getUserAudiosPortfolio($userId);

        return $this->response($userVoiceclipsPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function getUserCreditsPortfolio($userId)
    {
        $userCreditsPortfolio = $this->service->getUserCreditsPortfolio($userId);

        return $this->response($userCreditsPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function upsertUserImagePortfolio($userId)
    {
        $postRequest = $this->userImagePortfolioPostRequestMapper->map($this->request->all());

        $imageId = $postRequest->getImageId();
        $userImagesPortfolio = array ();

        if (empty($imageId)) {
            $this->validateRequest($postRequest->getValidationRules(),
                    $postRequest->getCustomMessages());
            $userImagesPortfolio = $this->service->createUserImagePortfolio($userId, $postRequest);
        } else {
            $this->validateRequest($postRequest->getUpdateValidationRules());
            $userImagesPortfolio = $this->service->updateUserImagePortfolio($userId, $postRequest);
        }
        return $this->response($userImagesPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function upsertUserVideoPortfolio($userId)
    {
        $postRequest = $this->userVideoPortfolioPostRequestMapper->map($this->request->all());
        $videoId = $postRequest->getVideoId();
        $userVideosPortfolio = array ();

        if (empty($videoId)) {
            $this->validateRequest($postRequest->getValidationRules(),
                    $postRequest->getCustomMessages());
            $userVideosPortfolio = $this->service->createUserVideoPortfolio($userId, $postRequest);
        } else {
            $this->validateRequest($postRequest->getUpdateValidationRules());
            $userVideosPortfolio = $this->service->updateUserVideoPortfolio($userId, $postRequest);
        }
        return $this->response($userVideosPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function upsertUserAudioPortfolio($userId)
    {
        $postRequest = $this->userAudioPortfolioPostRequestMapper->map($this->request->all());

        $audioId = $postRequest->getAudioId();
        $userAudiosPortfolio = array ();

        if (empty($audioId)) {
            $this->validateRequest($postRequest->getValidationRules());
            $userAudiosPortfolio = $this->service->createUserAudioPortfolio($userId, $postRequest);
        } else {
            $this->validateRequest($postRequest->getUpdateValidationRules());
            $userAudiosPortfolio = $this->service->updateUserAudioPortfolio($userId, $postRequest);
        }
        return $this->response($userAudiosPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function upsertUserCreditPortfolio($userId)
    {
        $postRequest = $this->userCreditPortfolioPostRequestMapper->map($this->request->all());

        $creditId = $postRequest->getCreditId();
        $userCreditsPortfolio = array ();

        if (empty($creditId)) {
            $this->validateRequest($postRequest->getValidationRules());
            $userCreditsPortfolio = $this->service->createUserCreditPortfolio($userId, $postRequest);
        } else {
            $this->validateRequest($postRequest->getUpdateValidationRules());
            $userCreditsPortfolio = $this->service->updateUserCreditPortfolio($userId, $postRequest);
        }
        return $this->response($userCreditsPortfolio);
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function deleteUserImagePortfolio($userId)
    {
        $imageId = $this->request->get('imageId', NULL);

        $this->service->deleteUserImagePortfolio($userId, $imageId);

        return $this->response();
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function deleteUserVideoPortfolio($userId)
    {
        $videoId = $this->request->get('videoId', NULL);

        $this->service->deleteUserVideoPortfolio($userId, $videoId);

        return $this->response();
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function deleteUserAudioPortfolio($userId)
    {
        $audioId = $this->request->get('audioId', NULL);

        $this->service->deleteUserAudioPortfolio($userId, $audioId);

        return $this->response();
    }

    /**
     *
     * @param integer $userId
     * @return Response
     */
    public function deleteUserCreditPortfolio($userId)
    {
        $creditId = $this->request->get('creditId', NULL);

        $this->service->deleteUserCreditPortfolio($userId, $creditId);

        return $this->response();
    }
}
