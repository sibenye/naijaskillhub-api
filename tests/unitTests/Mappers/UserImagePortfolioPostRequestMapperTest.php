<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserImagePortfolioPostRequestMapper;
use App\Models\Requests\UserImagePortfolioMetadataPostRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserImagePortfolioPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserImagePortfolioPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserImagePortfolioPostRequestMapper
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
        $this->mapper = new UserImagePortfolioPostRequestMapper();

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
                "caption" => "testCaption",
                "imageId" => 1234
        ];

        $expectedOut = new UserImagePortfolioMetadataPostRequest();
        $expectedOut->setCaption("testCaption");
        $expectedOut->setImageId(1234);

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
