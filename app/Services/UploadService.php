<?php
/**
 * @package App\Services
 */
namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use App\Utilities\NSHFileHandler;

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
        $filename = time() . '.' . $image->getClientOriginalExtension();

        $dirPath = public_path('images');
        return $this->fileHandler->saveImage($image, $filename, $dirPath);
    }
}