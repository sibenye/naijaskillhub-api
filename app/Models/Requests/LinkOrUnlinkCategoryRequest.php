<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * Link or Unlink Category Request
 *
 * @author silver.ibenye
 *
 */
class LinkOrUnlinkCategoryRequest implements IPostRequest
{
    /**
     *
     * @var integer[]
     */
    private $categoryIds;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        // TODO: Auto-generated method stub
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'categoryIds' => 'required|array'
        ];
    }

    /**
     * @return integer[]
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * @param integer[] $categoryIds
     * @return void
     */
    public function setCategoryIds($categoryIds)
    {
        $this->categoryIds = $categoryIds;
    }
}
