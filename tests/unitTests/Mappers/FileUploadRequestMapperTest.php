<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\FileUploadRequestMapper;
use App\Mappers\UserImagePortfolioPostRequestMapper;
use App\Models\Requests\FileUploadRequest;

/**
 * UserImagePortfolioPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class FileUploadRequestMapperTest extends \TestCase
{
    /**
     * @var UserProfileImagePostRequestMapper
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
        $this->mapper = new FileUploadRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "file" => 'binaryString',
                'contentType' => 'image/png',
                'contentLength' => 10000
        ];

        $expectedOut = new FileUploadRequest();
        $expectedOut->setFile('binaryString');
        $expectedOut->setContentType('image/png');
        $expectedOut->setContentLength(10000);

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
