<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

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
    public function saveImage(UploadedFile $image, $filename, $dirPath)
    {
        $path = $dirPath . '/' . $filename;
        $savedImage = Image::make($image->getRealPath())->save($path);

        return $savedImage;
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
