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
}
