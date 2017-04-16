<?php
/**
 * @package App\Services
 */
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\NSHFileHandler;
use Illuminate\Validation\ValidationException;
use App\Repositories\UserImagePortfolioRepository;
use App\Repositories\UserVideoPortfolioRepository;
use App\Repositories\UserAudioPortfolioRepository;
use App\Repositories\UserCreditPortfolioRepository;
use App\Repositories\CreditTypeRepository;
use App\Models\Requests\UserCreditPortfolioPostRequest;
use App\Models\Requests\UserAudioPortfolioMetadataPostRequest;
use App\Models\Requests\UserVideoPortfolioPostRequest;
use App\Models\Requests\UserImagePortfolioMetadataPostRequest;
use Illuminate\Support\Facades\Auth;

/**
 * UserPortfolio Service.
 *
 * @author silver.ibenye
 *
 */
class UserPortfolioService
{
    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var UserImagePortfolioRepository
     */
    private $userImagePortfolioRepository;

    /**
     *
     * @var UserVideoPortfolioRepository
     */
    private $userVideoPortfolioRepository;

    /**
     *
     * @var UserAudioPortfolioRepository
     */
    private $userAudioPortfolioRepository;

    /**
     *
     * @var UserCreditPortfolioRepository
     */
    private $userCreditPortfolioRepository;

    /**
     *
     * @var CreditTypeRepository
     */
    private $creditTypeRepository;

    /**
     *
     * @var NSHFileHandler
     */
    private $fileHandler;

    public function __construct(UserRepository $repository,
            UserImagePortfolioRepository $userImagePortfolioRepository,
            UserVideoPortfolioRepository $userVideoPortfolioRepository,
            UserAudioPortfolioRepository $userAudioPortfolioRepository,
            UserCreditPortfolioRepository $userCreditPortfolioRepository,
            CreditTypeRepository $creditTypeRepository, NSHFileHandler $fileHandler)
    {
        $this->userRepository = $repository;
        $this->userImagePortfolioRepository = $userImagePortfolioRepository;
        $this->userVideoPortfolioRepository = $userVideoPortfolioRepository;
        $this->userAudioPortfolioRepository = $userAudioPortfolioRepository;
        $this->userCreditPortfolioRepository = $userCreditPortfolioRepository;
        $this->creditTypeRepository = $creditTypeRepository;
        $this->fileHandler = $fileHandler;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getAllUserPortfolios($userId)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['images'] = $this->mapImagesResponse($user);
        $result ['videos'] = $this->mapVideosResponse($user);
        $result ['audios'] = $this->mapAudiosResponse($user);
        $result ['credits'] = $this->mapCreditsResponse($user);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserImagesPortfolio($userId, $imageId = NULL)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['images'] = $this->mapImagesResponse($user, $imageId);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserVideosPortfolio($userId, $videoId = NULL)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['videos'] = $this->mapVideosResponse($user, $videoId);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserAudiosPortfolio($userId, $audioId = NULL)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['audios'] = $this->mapAudiosResponse($user, $audioId);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserCreditsPortfolio($userId, $creditId = NULL)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['credits'] = $this->mapCreditsResponse($user, $creditId);

        return $result;
    }

    /**
     *
     * @param integer $userId
     * @param UserImagePortfolioMetadataPostRequest $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function updateUserImagePortfolioMetadata($userId,
            UserImagePortfolioMetadataPostRequest $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        $imageId = $request->getImageId();

        // ensure that the imageId is valid
        $existingImage = $user->images()
            ->where('id', $imageId)
            ->get();
        if (count($existingImage) == 0) {
            throw new ValidationException(NULL, 'Invalid imageId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request->getCaption();

        $this->userImagePortfolioRepository->update($imageId, $modelAttributes);

        $imagePortfolio = $this->getUserImagesPortfolio($userId, $imageId);

        $response = [ ];
        $response ['imageId'] = $imagePortfolio ['images'] [0] ['imageId'];
        $response ['caption'] = $imagePortfolio ['images'] [0] ['caption'];
        $response ['filePath'] = $imagePortfolio ['images'] [0] ['filePath'];
        $response ['fileName'] = $imagePortfolio ['images'] [0] ['fileName'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param UserVideoPortfolioPostRequest $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function createUserVideoPortfolio($userId, UserVideoPortfolioPostRequest $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        $caption = null;
        if (!empty($request->getCaption())) {
            $caption = $request->getCaption();
        }

        // save image portfolio.
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $caption;
        $modelAttribute ['videoUrl'] = $request->getVideoUrl();

        $savedVideoPortfolio = $this->userVideoPortfolioRepository->create($modelAttribute);

        $videoPortfolio = $this->getUserVideosPortfolio($userId, $savedVideoPortfolio->id);

        $response = [ ];
        $response ['videoId'] = $videoPortfolio ['videos'] [0] ['videoId'];
        $response ['caption'] = $videoPortfolio ['videos'] [0] ['caption'];
        $response ['videoUrl'] = $videoPortfolio ['videos'] [0] ['videoUrl'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param UserVideoPortfolioPostRequest $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function updateUserVideoPortfolio($userId, UserVideoPortfolioPostRequest $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        if (empty($request->getVideoUrl()) && empty($request->getCaption())) {
            throw new ValidationException(NULL, 'videoUrl or caption is required');
        }

        $videoId = $request->getVideoId();

        // ensure that the videoId is valid
        $existingVideo = $user->videos()
            ->where('id', $videoId)
            ->get();
        if (count($existingVideo) == 0) {
            throw new ValidationException(NULL, 'Invalid videoId');
        }

        $modelAttributes = array ();
        if (!empty($request->getCaption())) {
            $modelAttributes ['caption'] = $request->getCaption();
        }
        if (!empty($request->getVideoUrl())) {
            $modelAttributes ['videoUrl'] = $request ['videoUrl'];
        }

        $this->userVideoPortfolioRepository->update($videoId, $modelAttributes);

        $videoPortfolio = $this->getUserVideosPortfolio($userId, $videoId);

        $response = [ ];
        $response ['videoId'] = $videoPortfolio ['videos'] [0] ['videoId'];
        $response ['caption'] = $videoPortfolio ['videos'] [0] ['caption'];
        $response ['videoUrl'] = $videoPortfolio ['videos'] [0] ['videoUrl'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param array $request
     * @return array
     * @throws ValidationException
     */
    public function updateUserAudioPortfolioMetadata($userId,
            UserAudioPortfolioMetadataPostRequest $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        $audioId = $request->getAudioId();

        // ensure that the audioId is valid
        $existingAudio = $user->audios()
            ->where('id', $audioId)
            ->get();
        if (count($existingAudio) == 0) {
            throw new ValidationException(NULL, 'Invalid audioId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request->getCaption();

        $this->userAudioPortfolioRepository->update($audioId, $modelAttributes);

        $audioPortfolio = $this->getUserAudiosPortfolio($userId, $audioId);

        $response = [ ];
        $response ['audioId'] = $audioPortfolio ['audios'] [0] ['audioId'];
        $response ['caption'] = $audioPortfolio ['audios'] [0] ['caption'];
        $response ['filePath'] = $audioPortfolio ['audios'] [0] ['filePath'];
        $response ['fileName'] = $audioPortfolio ['audios'] [0] ['fileName'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param UserCreditPortfolioPostRequest $request
     * @return array
     * @throws ValidationException
     */
    public function createUserCreditPortfolio($userId, UserCreditPortfolioPostRequest $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        // validate creditTypeId.
        $creditType = $this->creditTypeRepository->getCreditTypeByName($request->getCreditType());
        if ($creditType == NULL) {
            throw new ValidationException(NULL, 'The creditType is invalid.');
        }

        // save image portfolio.
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $request->getCaption();
        $modelAttribute ['year'] = $request->getYear();
        $modelAttribute ['creditTypeId'] = $creditType->id;

        $savedCreditPortfolio = $this->userCreditPortfolioRepository->create($modelAttribute);

        $creditPortfolio = $this->getUserCreditsPortfolio($userId, $savedCreditPortfolio->id);

        $response = [ ];
        $response ['creditId'] = $creditPortfolio ['credits'] [0] ['creditId'];
        $response ['caption'] = $creditPortfolio ['credits'] [0] ['caption'];
        $response ['creditType'] = $creditPortfolio ['credits'] [0] ['creditType'];
        $response ['creditTypeId'] = $creditPortfolio ['credits'] [0] ['creditTypeId'];
        $response ['year'] = $creditPortfolio ['credits'] [0] ['year'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param UserCreditPortfolioPostRequest $request
     * @return array
     * @throws ValidationException
     */
    public function updateUserCreditPortfolio($userId, UserCreditPortfolioPostRequest $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        $creditType = NULL;
        if (!empty($request->getCreditType())) {
            // validate creditTypeId.
            $creditType = $this->creditTypeRepository->getCreditTypeByName(
                    $request->getCreditType());
            if ($creditType == NULL) {
                throw new ValidationException(NULL, 'The creditType is invalid.');
            }
        }

        $creditId = $request->getCreditId();

        // ensure that the creditId is valid
        $existingCredit = $this->userCreditPortfolioRepository->getByUserIdAndCreditId($userId,
                $creditId);
        if (count($existingCredit) == 0) {
            throw new ValidationException(NULL, 'Invalid creditId');
        }

        $modelAttributes = array ();
        if (!empty($request->getCaption())) {
            $modelAttributes ['caption'] = $request->getCaption();
        }
        if (!empty($request->getYear())) {
            $modelAttributes ['year'] = $request->getYear();
        }
        if (!empty($request->getCreditType())) {
            $modelAttributes ['creditTypeId'] = $creditType->id;
        }

        $this->userCreditPortfolioRepository->update($creditId, $modelAttributes);

        $creditPortfolio = $this->getUserCreditsPortfolio($userId, $creditId);

        $response = [ ];
        $response ['creditId'] = $creditPortfolio ['credits'] [0] ['creditId'];
        $response ['caption'] = $creditPortfolio ['credits'] [0] ['caption'];
        $response ['creditType'] = $creditPortfolio ['credits'] [0] ['creditType'];
        $response ['creditTypeId'] = $creditPortfolio ['credits'] [0] ['creditTypeId'];
        $response ['year'] = $creditPortfolio ['credits'] [0] ['year'];

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param integer $imageId
     * @return void
     * @throws ValidationException
     */
    public function deleteUserImagePortfolio($userId, $imageId)
    {
        // validate user.
        $user = $this->userRepository->get($userId);

        if (empty($imageId)) {
            throw new ValidationException(NULL, 'imageId is required');
        }

        // ensure that the imageId is valid
        $existingImage = $user->images()
            ->where('id', $imageId)
            ->get();
        if (count($existingImage) == 0) {
            throw new ValidationException(NULL, 'Invalid imageId');
        }

        // delete from database.
        $this->userImagePortfolioRepository->delete($imageId);

        // delete image file from directory.
        $filePath = $existingImage [0]->filePath;
        if (!empty($filePath)) {
            $filePath = env("PORTFOLIO_IMAGE_FOLDER") . $filePath;
            $this->fileHandler->deleteFile($filePath);
        }
    }

    /**
     *
     * @param integer $userId
     * @param integer $videoId
     * @return void
     * @throws ValidationException
     */
    public function deleteUserVideoPortfolio($userId, $videoId)
    {
        // validate user.
        $user = $this->userRepository->get($userId);

        if (empty($videoId)) {
            throw new ValidationException(NULL, 'videoId is required');
        }

        // ensure that the imageId is valid
        $existingVideo = $user->videos()
            ->where('id', $videoId)
            ->get();
        if (count($existingVideo) == 0) {
            throw new ValidationException(NULL, 'Invalid videoId');
        }

        // delete from database.
        $this->userVideoPortfolioRepository->delete($videoId);
    }

    /**
     *
     * @param integer $userId
     * @param integer $audioId
     * @return void
     * @throws ValidationException
     */
    public function deleteUserAudioPortfolio($userId, $audioId)
    {
        // validate user.
        $user = $this->userRepository->get($userId);

        if (empty($audioId)) {
            throw new ValidationException(NULL, 'audioId is required');
        }

        // ensure that the imageId is valid
        $existingAudio = $user->audios()
            ->where('id', $audioId)
            ->get();
        if (count($existingAudio) == 0) {
            throw new ValidationException(NULL, 'Invalid audioId');
        }

        // delete from database.
        $this->userAudioPortfolioRepository->delete($audioId);

        // delete audio file from directory.
        $filePath = $existingAudio [0]->filePath;
        if (!empty($filePath)) {
            $filePath = env("PORTFOLIO_AUDIO_FOLDER") . $filePath;
            $this->fileHandler->deleteFile($filePath);
        }
    }

    /**
     *
     * @param integer $userId
     * @param integer $creditId
     * @return void
     * @throws ValidationException
     */
    public function deleteUserCreditPortfolio($userId, $creditId)
    {
        // validate user.
        $this->userRepository->get($userId);

        if (empty($creditId)) {
            throw new ValidationException(NULL, 'creditId is required');
        }

        // ensure that the imageId is valid
        $existingCredit = $this->userCreditPortfolioRepository->getByUserIdAndCreditId($userId,
                $creditId);
        if (count($existingCredit) == 0) {
            throw new ValidationException(NULL, 'Invalid creditId');
        }

        // delete from database.
        $this->userCreditPortfolioRepository->delete($creditId);
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapImagesResponse(Model $user, $ImageId = NULL)
    {
        $images = empty($ImageId) ? $user->images : $user->images()
            ->where('id', $ImageId)
            ->get();
        $imagesContent = array ();
        foreach ($images as $key => $value) {
            $imagesContent [$key] ['imageId'] = $value->id;
            $imagesContent [$key] ['filePath'] = env("PORTFOLIO_IMAGE_FOLDER") . $value->filePath;
            $imagesContent [$key] ['fileName'] = $value->fileName;
            $imagesContent [$key] ['caption'] = $value->caption;
            $imagesContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $imagesContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
        }

        return $imagesContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapVideosResponse(Model $user, $videoId = NULL)
    {
        $videos = empty($videoId) ? $user->videos : $user->videos()
            ->where('id', $videoId)
            ->get();
        $videosContent = array ();
        foreach ($videos as $key => $value) {
            $videosContent [$key] ['videoId'] = $value->id;
            $videosContent [$key] ['videoUrl'] = $value->videoUrl;
            $videosContent [$key] ['caption'] = $value->caption;
            $videosContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $videosContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
        }

        return $videosContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapAudiosResponse(Model $user, $audioId = NULL)
    {
        $audios = empty($audioId) ? $user->audios : $user->audios()
            ->where('id', $audioId)
            ->get();
        $audiosContent = array ();
        foreach ($audios as $key => $value) {
            $audiosContent [$key] ['audioId'] = $value->id;
            $audiosContent [$key] ['filePath'] = env("PORTFOLIO_AUDIO_FOLDER") . $value->filePath;
            $audiosContent [$key] ['caption'] = $value->caption;
            $audiosContent [$key] ['fileName'] = $value->fileName;
            $audiosContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $audiosContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
        }
        return $audiosContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapCreditsResponse(Model $user, $creditId = NULL)
    {
        $credits = empty($creditId) ? $user->credits : $user->credits()
            ->where('id', $creditId)
            ->get();
        $creditsContent = array ();
        foreach ($credits as $key => $value) {
            $creditsContent [$key] ['creditId'] = $value->pivot->id;
            $creditsContent [$key] ['creditType'] = $value->name;
            $creditsContent [$key] ['creditTypeId'] = $value->pivot->creditTypeId;
            $creditsContent [$key] ['year'] = $value->pivot->year;
            $creditsContent [$key] ['caption'] = $value->pivot->caption;
            $creditsContent [$key] ['createdDate'] = $value->pivot->createdDate;
            $creditsContent [$key] ['modifiedDate'] = $value->pivot->modifiedDate;
        }

        return $creditsContent;
    }
}
