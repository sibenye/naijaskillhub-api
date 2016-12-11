<?php
/**
 * @package App\Services
 */
namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use App\Utilities\NSHFileHandler;
use Illuminate\Validation\ValidationException;

/**
 * Upload Service.
 * Handles image, video, and file uploads.
 * @author silver.ibenye
 *
 */
class UploadService
{
    private $fileHandler;

    public function __construct(NSHFileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     *
     * @param UploadedFile $image
     * @return string[]
     */
    public function uploadImage(UploadedFile $image)
    {
        if (!$image->isValid()) {
            throw new ValidationException(NULL, 'The image was not uploaded successfully.');
        }

        $filename = time() . '.' . $image->getClientOriginalExtension();

        $relativeDirPath = 'media/uploads/images';

        $dirPath = public_path($relativeDirPath);

        if (!$this->fileHandler->directoryExists($dirPath)) {
            $this->fileHandler->makeDirectory($dirPath);
        }

        $savedImage = $this->fileHandler->saveImage($image, $filename, $dirPath);

        $metadata = array ();
        $metadata ['filename'] = $filename;
        $metadata ['filesize'] = $savedImage->filesize();
        $metadata ['filePath'] = $relativeDirPath . '/' . $filename;
        $metadata ['width'] = $savedImage->width();
        $metadata ['height'] = $savedImage->height();

        return $metadata;
    }
}