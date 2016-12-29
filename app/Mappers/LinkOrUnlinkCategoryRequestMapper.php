<?php
/**
 * @package App\Mappers
 */
namespace App\Mappers;

use App\Models\Requests\LinkOrUnlinkCategoryRequest;

class LinkOrUnlinkCategoryRequestMapper implements IMapper
{

    /**
     * {@inheritDoc}
     * @see \App\Mappers\IMapper::map()
     * @param array $in
     * @return LinkOrUnlinkCategoryRequest
     */
    public function map($in)
    {
        $out = new LinkOrUnlinkCategoryRequest();

        $out->setCategoryIds(array_get($in, 'categoryIds', NULL));

        return $out;
    }
}

