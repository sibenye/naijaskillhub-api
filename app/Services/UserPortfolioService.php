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
use App\Models\Requests\UserAudioPortfolioPostRequest;
use App\Models\Requests\UserVideoPortfolioPostRequest;
use App\Models\Requests\UserImagePortfolioPostRequest;

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
    public function getUserImagesPortfolio($userId)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['images'] = $this->mapImagesResponse($user);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserVideosPortfolio($userId)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['videos'] = $this->mapVideosResponse($user);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserAudiosPortfolio($userId)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['voiceClips'] = $this->mapAudiosResponse($user);

        return $result;
    }

    /**
     *
     * @param integer $id
     * @return array Associative array.
     */
    public function getUserCreditsPortfolio($userId)
    {
        $user = $this->userRepository->get($userId);

        $result = array ();
        $result ['userId'] = $userId;
        $result ['credits'] = $this->mapCreditsResponse($user);

        return $result;
    }

    /**
     *
     * @param integer $userId
     * @param UserImagePortfolioPostRequest $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function createUserImagePortfolio($userId, UserImagePortfolioPostRequest $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        // ensure image was uploaded successfully.
        $image = $request->getImage();
        if (!$image->isValid()) {
            throw new ValidationException(NULL, 'The image was not uploaded successfully.');
        }

        // TODO: ensure image size is not more than 2MB

        // save image file.
        $filename = $userId . '_' . time() . '.' . $image->getClientOriginalExtension();

        $relativeDirPath = 'media/' . $userId . '/images';

        $dirPath = public_path($relativeDirPath);

        if (!$this->fileHandler->directoryExists($dirPath)) {
            $this->fileHandler->makeDirectory($dirPath);
        }

        $savedImage = $this->fileHandler->saveImageFile($image, $filename, $dirPath);

        // save image portfolio.
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $request->getCaption();
        $modelAttribute ['fileName'] = $filename;
        $modelAttribute ['fileSize'] = $savedImage->filesize();
        $modelAttribute ['filePath'] = $relativeDirPath . '/' . $filename;
        $modelAttribute ['width'] = $savedImage->width();
        $modelAttribute ['height'] = $savedImage->height();

        $this->userImagePortfolioRepository->create($modelAttribute);

        return $this->getUserImagesPortfolio($userId);
    }

    /**
     *
     * @param integer $userId
     * @param UserImagePortfolioPostRequest $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function updateUserImagePortfolio($userId, UserImagePortfolioPostRequest $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        $imageId = $request->getImageId();

        // ensure that the imageId is valid
        $existingImage = $user->images()->where('id', $imageId)->get();
        if (count($existingImage) == 0) {
            throw new ValidationException(NULL, 'Invalid imageId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request->getCaption();

        $this->userImagePortfolioRepository->update($imageId, $modelAttributes);

        return $this->getUserImagesPortfolio($userId);
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

        $this->userVideoPortfolioRepository->create($modelAttribute);

        return $this->getUserVideosPortfolio($userId);
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
        $existingVideo = $user->videos()->where('id', $videoId)->get();
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

        return $this->getUserVideosPortfolio($userId);
    }

    /**
     *
     * @param integer $userId
     * @param UserAudioPortfolioPostRequest $request
     * @return array
     * @throws ValidationException
     */
    public function createUserAudioPortfolio($userId, UserAudioPortfolioPostRequest $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        // ensure image was uploaded successfully.
        $audio = $request->getAudio();
        if (!$audio->isValid()) {
            throw new ValidationException(NULL, 'The audio file was not uploaded successfully.');
        }

        // TODO: ensure audio file size is not more than 2MB

        // save audio file.
        $filename = $userId . '_' . time() . '.' . $audio->getClientOriginalExtension();

        $relativeDirPath = 'media/' . $userId . '/audios';

        $dirPath = public_path($relativeDirPath);

        if (!$this->fileHandler->directoryExists($dirPath)) {
            $this->fileHandler->makeDirectory($dirPath);
        }

        $savedAudio = $this->fileHandler->saveAudioFile($audio, $filename, $dirPath);

        $caption = '';
        if (empty($request->getCaption())) {
            $caption = $audio->getClientOriginalName();
        } else {
            $caption = $request->getCaption();
        }

        // save image portfolio.
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $caption;
        $modelAttribute ['fileName'] = $filename;
        $modelAttribute ['fileSize'] = $savedAudio->getSize();
        $modelAttribute ['filePath'] = $relativeDirPath . '/' . $filename;

        $this->userAudioPortfolioRepository->create($modelAttribute);

        return $this->getUserAudiosPortfolio($userId);
    }

    /**
     *
     * @param integer $userId
     * @param array $request
     * @return array
     * @throws ValidationException
     */
    public function updateUserAudioPortfolio($userId, UserAudioPortfolioPostRequest $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        $audioId = $request->getAudioId();

        // ensure that the audioId is valid
        $existingAudio = $user->audios()->where('id', $audioId)->get();
        if (count($existingAudio) == 0) {
            throw new ValidationException(NULL, 'Invalid audioId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request->getCaption();

        $this->userAudioPortfolioRepository->update($audioId, $modelAttributes);

        return $this->getUserAudiosPortfolio($userId);
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

        $this->userCreditPortfolioRepository->create($modelAttribute);

        return $this->getUserCreditsPortfolio($userId);
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

        return $this->getUserCreditsPortfolio($userId);
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
        $existingImage = $user->images()->where('id', $imageId)->get();
        if (count($existingImage) == 0) {
            throw new ValidationException(NULL, 'Invalid imageId');
        }

        // delete from database.
        $this->userImagePortfolioRepository->delete($imageId);

        // delete image file from directory.
        $relativeDirPath = $existingImage [0]->filePath;
        if (!empty($relativeDirPath)) {
            $absolutePath = public_path($relativeDirPath);
            if ($this->fileHandler->fileExists($absolutePath)) {
                $this->fileHandler->deleteFile($absolutePath);
            }
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
        $existingVideo = $user->videos()->where('id', $videoId)->get();
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
        $existingAudio = $user->audios()->where('id', $audioId)->get();
        if (count($existingAudio) == 0) {
            throw new ValidationException(NULL, 'Invalid audioId');
        }

        // delete from database.
        $this->userAudioPortfolioRepository->delete($audioId);

        // delete audio file from directory.
        $relativeDirPath = $existingAudio [0]->filePath;
        if (!empty($relativeDirPath)) {
            $absolutePath = public_path($relativeDirPath);
            if ($this->fileHandler->fileExists($absolutePath)) {
                $this->fileHandler->deleteFile($absolutePath);
            }
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
    private function mapImagesResponse(Model $user)
    {
        $images = $user->images;
        $imagesContent = array ();
        foreach ($images as $key => $value) {
            $imagesContent [$key] ['imageId'] = $value->id;
            $imagesContent [$key] ['filePath'] = $value->filePath;
            $imagesContent [$key] ['fileName'] = $value->fileName;
            $imagesContent [$key] ['fileSize'] = $value->fileSize;
            $imagesContent [$key] ['caption'] = $value->caption;
            $imagesContent [$key] ['width'] = $value->width;
            $imagesContent [$key] ['height'] = $value->height;
            $imagesContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $imagesContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
            ;
        }

        return $imagesContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapVideosResponse(Model $user)
    {
        $videos = $user->videos;
        $videosContent = array ();
        foreach ($videos as $key => $value) {
            $videosContent [$key] ['videoId'] = $value->id;
            $videosContent [$key] ['videoUrl'] = $value->videoUrl;
            $videosContent [$key] ['caption'] = $value->caption;
            $videosContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $videosContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
            ;
        }

        return $videosContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapAudiosResponse(Model $user)
    {
        $audios = $user->audios;
        $audiosContent = array ();
        foreach ($audios as $key => $value) {
            $audiosContent [$key] ['audioId'] = $value->id;
            $audiosContent [$key] ['filePath'] = $value->filePath;
            $audiosContent [$key] ['caption'] = $value->caption;
            $audiosContent [$key] ['fileName'] = $value->fileName;
            $audiosContent [$key] ['fileSize'] = $value->fileSize;
            $audiosContent [$key] ['createdDate'] = $value->createdDate->toDateTimeString();
            $audiosContent [$key] ['modifiedDate'] = $value->modifiedDate->toDateTimeString();
            ;
        }
        return $audiosContent;
    }

    /**
     *
     * @param Model $user
     * @return array Associative array.
     */
    private function mapCreditsResponse(Model $user)
    {
        $credits = $user->credits;
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
