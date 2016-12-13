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
     * @var NSHFileHandler
     */
    private $fileHandler;

    public function __construct(UserRepository $repository,
            UserImagePortfolioRepository $userImagePortfolioRepository,
            UserVideoPortfolioRepository $userVideoPortfolioRepository,
            UserAudioPortfolioRepository $userAudioPortfolioRepository, NSHFileHandler $fileHandler)
    {
        $this->userRepository = $repository;
        $this->userImagePortfolioRepository = $userImagePortfolioRepository;
        $this->userVideoPortfolioRepository = $userVideoPortfolioRepository;
        $this->userAudioPortfolioRepository = $userAudioPortfolioRepository;
        $this->fileHandler = $fileHandler;
    }

    /**
     *
     * @param string $id
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
     * @param string $id
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
     * @param string $id
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
     * @param string $id
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
     * @param string $id
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
     * @param string $userId
     * @param array $request Associative array of the request.
     * @return array Associative array.
     * @throws ValidationException
     */
    public function createUserImagePortfolio($userId, $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        // ensure image is in the request
        if (empty($request ['image'])) {
            throw new ValidationException(NULL, 'Image file is required.');
        }

        // ensure image was uploaded successfully.
        $image = $request ['image'];
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
        $modelAttribute ['caption'] = $request ['caption'];
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
     * @param string $userId
     * @param array $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function updateUserImagePortfolio($userId, $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        if (!array_key_exists('imageId', $request) || empty($request ['imageId'])) {
            throw new ValidationException(NULL, 'imageId is required');
        }

        if (!array_key_exists('caption', $request) || empty($request ['caption'])) {
            throw new ValidationException(NULL, 'caption is required');
        }

        $imageId = $request ['imageId'];

        // ensure that the imageId is valid
        $existingImage = $user->images()->where('id', $imageId)->get();
        if (count($existingImage) == 0) {
            throw new ValidationException(NULL, 'Invalid imageId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request ['caption'];

        $this->userImagePortfolioRepository->update($imageId, $modelAttributes);

        return $this->getUserImagesPortfolio($userId);
    }

    /**
     *
     * @param string $userId
     * @param array $request Associative array of the request.
     * @return array Associative array.
     * @throws ValidationException
     */
    public function createUserVideoPortfolio($userId, $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        if (!array_key_exists('videoUrl', $request) || empty($request ['videoUrl'])) {
            throw new ValidationException(NULL, 'videoUrl is required');
        }
        $caption = null;
        if (array_key_exists('caption', $request)) {
            $caption = $request ['caption'];
        }

        // save image portfolio.
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $caption;
        $modelAttribute ['videoUrl'] = $request ['videoUrl'];

        $this->userVideoPortfolioRepository->create($modelAttribute);

        return $this->getUserVideosPortfolio($userId);
    }

    /**
     *
     * @param string $userId
     * @param array $request
     * @return array Associative array.
     * @throws ValidationException
     */
    public function updateUserVideoPortfolio($userId, $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        if (!array_key_exists('videoId', $request) || empty($request ['videoId'])) {
            throw new ValidationException(NULL, 'videoId is required');
        }

        if ((!array_key_exists('videoUrl', $request) || empty($request ['videoUrl'])) &&
                 (!array_key_exists('caption', $request) || empty($request ['caption']))) {
            throw new ValidationException(NULL, 'videoUrl or caption is required');
        }

        $videoId = $request ['videoId'];

        // ensure that the videoId is valid
        $existingVideo = $user->videos()->where('id', $videoId)->get();
        if (count($existingVideo) == 0) {
            throw new ValidationException(NULL, 'Invalid videoId');
        }

        $modelAttributes = array ();
        if (array_key_exists('caption', $request)) {
            $modelAttributes ['caption'] = $request ['caption'];
        }
        if (array_key_exists('videoUrl', $request)) {
            $modelAttributes ['videoUrl'] = $request ['videoUrl'];
        }

        $this->userVideoPortfolioRepository->update($videoId, $modelAttributes);

        return $this->getUserVideosPortfolio($userId);
    }

    /**
     *
     * @param string $userId
     * @param string $request
     * @return array
     * @throws ValidationException
     */
    public function createUserAudioPortfolio($userId, $request)
    {
        // validate userId.
        $this->userRepository->get($userId);

        // ensure image is in the request
        if (empty($request ['audio'])) {
            throw new ValidationException(NULL, 'Audio file is required.');
        }

        // ensure image was uploaded successfully.
        $audio = $request ['audio'];
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
        if (!array_key_exists('caption', $request) || empty($request ['caption'])) {
            $caption = $audio->getClientOriginalName();
        } else {
            $caption = $request ['caption'];
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
     * @param string $userId
     * @param array $request
     * @return array
     * @throws ValidationException
     */
    public function updateUserAudioPortfolio($userId, $request)
    {
        // validate userId.
        $user = $this->userRepository->get($userId);

        if (!array_key_exists('audioId', $request) || empty($request ['audioId'])) {
            throw new ValidationException(NULL, 'audioId is required');
        }

        if (!array_key_exists('caption', $request) || empty($request ['caption'])) {
            throw new ValidationException(NULL, 'caption is required');
        }

        $audioId = $request ['audioId'];

        // ensure that the audioId is valid
        $existingAudio = $user->audios()->where('id', $audioId)->get();
        if (count($existingAudio) == 0) {
            throw new ValidationException(NULL, 'Invalid audioId');
        }

        $modelAttributes = array ();
        $modelAttributes ['caption'] = $request ['caption'];

        $this->userAudioPortfolioRepository->update($audioId, $modelAttributes);

        return $this->getUserAudiosPortfolio($userId);
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
            $creditsContent [$key] ['creditId'] = $value->id;
            $creditsContent [$key] ['creditTypeName'] = $value->name;
            $creditsContent [$key] ['creditTypeId'] = $value->pivot->creditTypeId;
            $creditsContent [$key] ['year'] = $value->pivot->year;
            $creditsContent [$key] ['caption'] = $value->pivot->caption;
            $creditsContent [$key] ['createdDate'] = $value->pivot->createdDate;
            $creditsContent [$key] ['modifiedDate'] = $value->pivot->modifiedDate;
        }

        return $creditsContent;
    }
}
