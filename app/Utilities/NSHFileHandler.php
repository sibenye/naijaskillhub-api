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

    public function saveImage(UploadedFile $image, $filename, $dirPath)
    {
        $path = $dirPath . '/' . $filename;
        $savedImage = Image::make($image->getRealPath())->save($path);

        $metadata = array ();
        $metadata ['filename'] = $filename;
        $metadata ['filesize'] = $savedImage->filesize();
        $metadata ['width'] = $savedImage->width();
        $metadata ['height'] = $savedImage->height();

        return $metadata;
    }
}
