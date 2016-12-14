<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserAudioPortfolioPostRequestMapper;
use App\Models\Requests\UserAudioPortfolioPostRequest;
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

        $this->audioMock = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "audio" => $this->audioMock,
                "caption" => "testCaption"
        ];

        $expectedOut = new UserAudioPortfolioPostRequest();
        $expectedOut->setAudio($this->audioMock);
        $expectedOut->setCaption("testCaption");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
