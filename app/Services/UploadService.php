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

    public function __construct(UserRepository $repository,
            UserAttributeRepository $userAttributeRepository, NSHFileHandler $fileHandler)
    {
        $this->userRepository = $repository;
        $this->userAttributeRepository = $userAttributeRepository;
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

        if (!$this->fileHandler->contentTypeIsImage($contentType)) {
            throw new ValidationException(NULL, 'The content is not an image.');
        }

        $userAttribute = $this->userAttributeRepository->getUserAttributeByName('profileImage',
                true);

        // delete exsiting profile image file if any
        $attributeName = [
                'profileImage'
        ];
        $existingProfileImage = $this->userRepository->getUserAttributes($user->id, $attributeName);

        if (!$existingProfileImage->isEmpty()) {
            $existingProfileImageFilePath = $existingProfileImage->first()->pivot->attributeValue;
            $this->fileHandler->deleteFile($existingProfileImageFilePath);
        }

        // save image file.
        $filename = $userId . '_profile_' . time() . '.' .
                 $this->fileHandler->getImageExtension($contentType);

        $filePath = $filename;

        $this->fileHandler->uploadFile($filename, $image);

        // save image filePath
        $attributesCollection = array ();

        $attributesCollection [0] ['attributeId'] = $userAttribute ['id'];
        $attributesCollection [0] ['attributeValue'] = $filePath;

        $this->userRepository->upsertUserAttributeValue($user, $attributesCollection);

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
    private function validateUploadRequest(FileUploadRequest $request)
    {
        if (empty($request->getFile())) {
            throw new ValidationException(NULL, 'The File to be uploaded is required');
        }

        if (empty($request->getContentType())) {
            throw new ValidationException(NULL, 'The Content-Type header is required');
        }

        var_dump('SIZE: ' . $this->fileHandler->getFileSize($request->getFile()));
        if ($this->fileHandler->getFileSize($request->getFile()) > 2048000) {
            throw new ValidationException(NULL, 'The image size is more than 2MB.');
        }
    }
}