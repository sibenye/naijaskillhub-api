<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;

/**
 *
 * @author silver.ibenye
 *
 */
class NSHFileHandler
{

    /**
     *
     * @param UploadedFile $image
     * @param string $filename
     * @param string $dirPath
     * @return \Intervention\Image\Image
     */
    public function saveImageFile(UploadedFile $image, $filename, $dirPath)
    {
        $path = $dirPath . '/' . $filename;
        $savedImage = Image::make($image->getRealPath())->save($path);
        // free memory resource.
        $image->destroy();

        return $savedImage;
    }

    /**
     *
     * @param UploadedFile $audio
     * @param string $filename
     * @param string $dirPath
     * @return File
     */
    public function saveAudioFile(UploadedFile $audio, $filename, $dirPath)
    {
        $savedAudio = $audio->move($dirPath, $filename);

        return @$savedAudio;
    }

    /**
     *
     * @param string $dirPath
     * @param number $mode
     * @return void
     */
    public function makeDirectory($dirPath, $mode = 0777)
    {
        mkdir($dirPath, $mode, true);
    }

    /**
     *
     * @param string $dirPath
     * @return boolean
     */
    public function directoryExists($dirPath)
    {
        return is_dir($dirPath);
    }

    /**
     *
     * @param string $filename
     * @return boolean
     */
    public function fileExists($filename)
    {
        return is_file($filename);
    }

    /**
     *
     * @param string $filename Path to file.
     * @return void
     */
    public function deleteFile($filename)
    {
        unlink($filename);
    }
}
