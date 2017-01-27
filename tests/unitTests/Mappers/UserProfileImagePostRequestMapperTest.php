<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserImagePortfolioPostRequestMapper;
use App\Models\Requests\UserImagePortfolioPostRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Mappers\UserProfileImagePostRequestMapper;
use App\Models\Requests\UserProfileImagePostRequest;

/**
 * UserImagePortfolioPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserProfileImagePostRequestMapperTest extends \TestCase
{
    /**
     * @var UserProfileImagePostRequestMapper
     */
    private $mapper;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $imageMock;

    /**
     *
     * {@inheritDoc}
     * @see \Laravel\Lumen\Testing\TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        $this->mapper = new UserProfileImagePostRequestMapper();

        $this->imageMock = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "image" => $this->imageMock
        ];

        $expectedOut = new UserProfileImagePostRequest();
        $expectedOut->setImage($this->imageMock);

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
