<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;
use App\Utilities\NSHSFTPClientWrapper;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\Flysystem\Facades\Flysystem;

/**
 *
 * @author silver.ibenye
 *
 */
class NSHFileHandler
{
    const IMAGE_FILE_TYPE = 'image';
    const AUDIO_FILE_TYPE = 'audio';

    /**
     *
     * @var NSHSFTPClientWrapper
     */
    private $sftpClientWrapper;

    /**
     *
     * @var boolean
     */
    private $sftpEnabled;

    /**
     *
     * @var FlysystemManager
     */
    private $flysystem;

    public function __construct(NSHSFTPClientWrapper $sftpClientWrapper, FlysystemManager $flysystem)
    {
        $this->sftpClientWrapper = $sftpClientWrapper;
        $this->sftpEnabled = env("SFTP_ENABLED");
        $this->flysystem = $flysystem;
    }

    /**
     * Checks if contentType is an image.
     *
     * @param string $contentType
     * @return boolean
     */
    public function fileTypeIsImage($contentType)
    {
        return ($this->getFileType($contentType) == self::IMAGE_FILE_TYPE);
    }

    /**
     * Checks if contentType is an audio.
     *
     * @param string $contentType
     * @return boolean
     */
    public function fileTypeIsAudio($contentType)
    {
        return ($this->getFileType($contentType) == self::AUDIO_FILE_TYPE);
    }

    /**
     *
     * @param string $contentType
     * @return string
     */
    public function getFileType($contentType)
    {
        return preg_split('/\//', $contentType) [0];
    }

    /**
     *
     * @param string $contentType
     * @return string
     */
    public function getFileExtension($contentType)
    {
        return preg_split('/\//', $contentType) [1];
    }

    /**
     *
     * @param UploadedFile $image
     * @return \Intervention\Image\Image
     */
    public function makeImage(UploadedFile $image)
    {
        return Image::make($image->getRealPath());
    }

    /**
     *
     * @param string $dirPath
     * @param number $mode
     * @return void
     */
    public function makeLocalDirectory($dirPath, $mode = 0777)
    {
        mkdir($dirPath, $mode, true);
    }

    /**
     *
     * @param string $dirPath
     * @return boolean
     */
    public function localDirectoryExists($dirPath)
    {
        return is_dir($dirPath);
    }

    /**
     *
     * @param string $filename
     * @return boolean
     */
    public function localFileExists($filename)
    {
        return is_file($filename);
    }

    public function deleteFile($filePath)
    {
        if ($this->flysystem->has($filePath)) {
            $this->flysystem->delete($filePath);
        }
        /*
         * if ($this->sftpEnabled) {
         * $this->deleteFileOnFTP($filePath);
         * } else {
         * $filePath = public_path($filePath);
         * $this->deleteLocalFile($filePath);
         * }
         */
    }

    /**
     *
     * @param string $filename Path to file.
     * @return void
     */
    public function deleteLocalFile($filePath)
    {
        if ($this->localFileExists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     *
     * @param string $filePath
     * @return void
     */
    public function deleteFileOnFTP($filePath)
    {
        if ($this->sftpClientWrapper->fileExists($filePath)) {
            $this->sftpClientWrapper->deleteFile($filePath);
        }
    }

    /**
     *
     * @param string $fileName
     * @param string $fileContent
     * @return void
     */
    public function uploadFile($filePath, $fileContent)
    {
        $this->flysystem->write($filePath, $fileContent);
    }

    /**
     *
     * @param string $fileName
     * @param UploadedFile $file
     * @param string $destinationFolder
     * @return void
     */
    public function uploadFileToLocal($fileName, UploadedFile $file, $destinationFolder)
    {
        // check if destination folder exists, if not create it
        if (!$this->localDirectoryExists($destinationFolder)) {
            $this->makeLocalDirectory($destinationFolder);
        }

        $file->move($destinationFolder, $fileName);
    }

    /**
     *
     * @param string $fileName
     * @param UploadedFile $file
     * @param string $destinationFolder
     * @return void
     */
    public function uploadFileToFTP($fileName, UploadedFile $file, $destinationFolder)
    {
        // check if destination folder exists, if not create it
        if (!$this->sftpClientWrapper->fileExists($destinationFolder)) {
            $this->sftpClientWrapper->makeDirectory($destinationFolder);
        }
        // change directory to destination folder
        $this->sftpClientWrapper->changeDirectory($destinationFolder);
        // upload the file.
        $this->sftpClientWrapper->uploadFile($fileName, $file->getRealPath());
    }

    /**
     * Gets the size of a file.
     *
     * @param string $fileString
     * @return mixed|int
     */
    public function getFileSize($fileString)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($fileString, '8bit');
        } else {
            return strlen($fileString);
        }
    }
}
