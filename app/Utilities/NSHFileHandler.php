<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;
use App\Utilities\NSHSFTPClientWrapper;

/**
 *
 * @author silver.ibenye
 *
 */
class NSHFileHandler
{
    private $sftpClientWrapper;
    private $sftpEnabled;

    public function __construct(NSHSFTPClientWrapper $sftpClientWrapper)
    {
        $this->sftpClientWrapper = $sftpClientWrapper;
        $this->sftpEnabled = env("SFTP_ENABLED");
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
        if ($this->sftpEnabled) {
            $this->deleteFileOnFTP($filePath);
        } else {
            $filePath = public_path($filePath);
            $this->deleteLocalFile($filePath);
        }
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
     * @param UploadedFile $file
     * @param string $destinationFolder
     * @return void
     */
    public function uploadFile($fileName, UploadedFile $file, $destinationFolder)
    {
        if ($this->sftpEnabled) {
            $this->uploadFileToFTP($fileName, $file, $destinationFolder);
        } else {
            $destinationFolder = public_path($destinationFolder);
            $this->uploadFileToLocal($fileName, $file, $destinationFolder);
        }
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
}
