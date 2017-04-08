<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserAudioPortfolioPostRequestMapper;
use App\Models\Requests\UserAudioPortfolioMetadataPostRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserAudioPortfolioPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserAudioPortfolioPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserAudioPortfolioPostRequestMapper
     */
    private $mapper;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $audioMock;

    /**
     *
     * {@inheritDoc}
     * @see \Laravel\Lumen\Testing\TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        $this->mapper = new UserAudioPortfolioPostRequestMapper();

        $this->audioMock = $this->getMockBuilder(UploadedFile::class)
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
                "audioId" => 1234
        ];

        $expectedOut = new UserAudioPortfolioMetadataPostRequest();
        $expectedOut->setCaption("testCaption");
        $expectedOut->setAudioId(1234);

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
