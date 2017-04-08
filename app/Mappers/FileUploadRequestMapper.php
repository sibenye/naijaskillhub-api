<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\FileUploadRequest;

/**
 * FileUploadRequest Mapper.
 *
 * @author silver.ibenye
 *
 */
class FileUploadRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return FileUploadRequest
     */
    public function map($in)
    {
        $out = new FileUploadRequest();

        $out->setFile(array_get($in, 'file', NULL));
        $out->setContentType(array_get($in, 'contentType', NULL));
        $out->setCaption(array_get($in, 'caption', NULL));

        return $out;
    }
}
