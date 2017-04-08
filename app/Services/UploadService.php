<?php
/**
 * @package App\Services
 */
namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use App\Utilities\NSHFileHandler;
use Illuminate\Validation\ValidationException;
use App\Repositories\UserRepository;
use App\Repositories\UserAttributeRepository;
use App\Models\Requests\FileUploadRequest;
use App\Repositories\UserImagePortfolioRepository;
use App\Repositories\UserAudioPortfolioRepository;

/**
 * Upload Service.
 * Handles image, video, and file uploads.
 * @author silver.ibenye
 *
 */
class UploadService
{
    /**
     *
     * @var NSHFileHandler
     */
    private $fileHandler;

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var UserAttributeRepository
     */
    private $userAttributeRepository;
    /**
     *
     * @var UserImagePortfolioRepository
     */
    private $userImagePortfolioRepository;

    /**
     *
     * @var UserAudioPortfolioRepository
     */
    private $userAudioPortfolioRepository;

    public function __construct(UserRepository $repository,
            UserAttributeRepository $userAttributeRepository,
            UserImagePortfolioRepository $userImagePortfolioRepository,
            UserAudioPortfolioRepository $userAudioPortfolioRepository, NSHFileHandler $fileHandler)
    {
        $this->userRepository = $repository;
        $this->userAttributeRepository = $userAttributeRepository;
        $this->userImagePortfolioRepository = $userImagePortfolioRepository;
        $this->userAudioPortfolioRepository = $userAudioPortfolioRepository;
        $this->fileHandler = $fileHandler;
    }

    /**
     *
     * @param integer $userId
     * @param UserProfileImagePostRequest $request
     * @throws ValidationException
     * @return array
     */
    public function uploadUserProfileImage($userId, FileUploadRequest $request)
    {
        $this->validateUploadRequest($request);

        // validate userId.
        $user = $this->userRepository->get($userId);

        // ensure the content is of type image.
        $image = $request->getFile();
        $contentType = $request->getContentType();

        if (!$this->fileHandler->fileTypeIsImage($contentType)) {
            throw new ValidationException(NULL, 'The content is not an image file.');
        }

        $userAttribute = $this->userAttributeRepository->getUserAttributeByName('profileImage',
                true);

        // delete exsiting profile image file if any
        $attributeName = [
                'profileImage'
        ];
        $existingProfileImage = $this->userRepository->getUserAttributes($user->id, $attributeName);

        if (!$existingProfileImage->isEmpty() &&
                 !empty($existingProfileImage->first()->attributeValue)) {
            $existingProfileImageFilePath = $existingProfileImage->first()->attributeValue;
            $this->fileHandler->deleteFile(
                    env("PROFILE_IMAGE_FOLDER") . $existingProfileImageFilePath);
        }

        // save image file.
        $filename = $userId . '_profile_' . time() . '.' .
                 $this->fileHandler->getFileExtension($contentType);

        $filePath = env("PROFILE_IMAGE_FOLDER") . $filename;

        $this->fileHandler->uploadFile($filePath, $image);

        // save image filePath in database
        // note: we save the fileName as the filePath.
        $attributesCollection = array ();

        $attributesCollection [0] ['attributeId'] = $userAttribute ['id'];
        $attributesCollection [0] ['attributeValue'] = $filename;

        $this->userRepository->upsertUserAttributeValue($user, $attributesCollection);

        $response = array ();
        $response ['filePath'] = $filePath;

        return $response;
    }

    /**
     *
     * @param integer $userId
     * @param string $location
     * @param FileUploadRequest $request
     * @throws ValidationException
     * @return array
     */
    public function uploadUserPortfolioImage($userId, FileUploadRequest $request)
    {
        $this->validateUploadRequest($request);

        // validate userId.
        $this->userRepository->get($userId);

        // ensure the content is of type image.
        $image = $request->getFile();
        $contentType = $request->getContentType();

        if (!$this->fileHandler->fileTypeIsImage($contentType)) {
            throw new ValidationException(NULL, 'The content is not an image file.');
        }

        $filename = $userId . '_' . time() . '.' . $this->fileHandler->getFileExtension(
                $contentType);

        $filePath = env("PORTFOLIO_IMAGE_FOLDER") . $filename;

        $this->fileHandler->uploadFile($filePath, $image);

        // save metadata
        $modelAttribute = array ();
        $modelAttribute ['userId'] = $userId;
        $modelAttribute ['caption'] = $request->getCaption();
        $modelAttribute ['fileName'] = $filename;
        $modelAttribute ['filePath'] = $filename; // we save the fileName as the filePath.

        $this->userImagePortfolioRepository->create($modelAttribute);

        $response = array ();
        $response ['filePath'] = $filePath;

        return $response;
    }

    public function uploadUserPortfolioAudio($userId, $location, FileUploadRequest $request)
    {
        $this->validateUploadRequest($request);

        // validate userId.
        $this->userRepository->get($userId);

        // ensure the content is of type image.
        $image = $request->getFile();
        $contentType = $request->getContentType();

        if (!$this->fileHandler->fileTypeIsAudio($contentType)) {
            throw new ValidationException(NULL, 'The content is not an audio file.');
        }

        if (empty($location)) {
            throw new ValidationException(NULL, 'The location header is required.');
        }

        $filePath = $location;

        $this->fileHandler->uploadFile($filePath, $image);

        $response = array ();
        $response ['filePath'] = $filePath;

        return $response;
    }

    /**
     * Validates the upload request.
     *
     * @param FileUploadRequest $request
     * @throws ValidationException
     */
    public function validateUploadRequest(FileUploadRequest $request)
    {
        if (empty($request->getFile())) {
            throw new ValidationException(NULL, 'The File to be uploaded is required');
        }

        if (empty($request->getContentType())) {
            throw new ValidationException(NULL, 'The Content-Type header is required');
        }

        // ensure the contentType is in the right format
        if (!preg_match('/\w\/\w/', $request->getContentType())) {
            throw new ValidationException(NULL,
                'The Content-Type header should be in this format "{type}/{type extension}".');
        }

        if ($this->fileHandler->getFileSize($request->getFile()) > 2048000) {
            throw new ValidationException(NULL, 'The image size is more than 2MB.');
        }
    }
}