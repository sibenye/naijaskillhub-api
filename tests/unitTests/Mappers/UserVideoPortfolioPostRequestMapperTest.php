<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserVideoPortfolioPostRequestMapper;
use App\Models\Requests\UserVideoPortfolioPostRequest;

/**
 * UserVideoPortfolioPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserVideoPortfolioPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserVideoPortfolioPostRequestMapper
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
        $this->mapper = new UserVideoPortfolioPostRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "videoUrl" => "testUrl",
                "caption" => "testCaption",
                "description" => "testDescription"
        ];

        $expectedOut = new UserVideoPortfolioPostRequest();
        $expectedOut->setVideoUrl("testUrl");
        $expectedOut->setCaption("testCaption");
        $expectedOut->setDescription("testDescription");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
