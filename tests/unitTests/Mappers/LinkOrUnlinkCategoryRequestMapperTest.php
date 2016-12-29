<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\LinkOrUnlinkCategoryRequestMapper;
use App\Models\Requests\LinkOrUnlinkCategoryRequest;

/**
 * LinkOrUnlinkCategoryRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class LinkOrUnlinkCategoryRequestMapperTest extends \TestCase
{
    /**
     * @var LinkOrUnlinkCategoryRequestMapper
     */
    private $mapper;

    /**
     *
     * {@inheritDoc}
     * @see \Laravel\Lumen\Testing\TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        $this->mapper = new LinkOrUnlinkCategoryRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                'categoryIds' => [
                        1,
                        3,
                        18
                ]
        ];

        $expectedOut = new LinkOrUnlinkCategoryRequest();
        $expectedOut->setCategoryIds(
                [
                        1,
                        3,
                        18
                ]);

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
