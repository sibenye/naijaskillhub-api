<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserEmailChangePostRequestMapper;
use App\Models\Requests\UserEmailChangePostRequest;

/**
 * UserEmailChangePostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserEmailChangePostRequestMapperTest extends \TestCase
{
    /**
     * @var UserEmailChangePostRequestMapper
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
        $this->mapper = new UserEmailChangePostRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                'newEmailAddress' => 'test2@mail'
        ];

        $expectedOut = new UserEmailChangePostRequest();
        $expectedOut->setNewEmailAddress('test2@mail');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}