<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserChangeEmailPostRequestMapper;
use App\Models\Requests\UserChangeEmailPostRequest;

/**
 * UserChangeEmailPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserChangeEmailPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserChangeEmailPostRequestMapper
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
        $this->mapper = new UserChangeEmailPostRequestMapper();
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

        $expectedOut = new UserChangeEmailPostRequest();
        $expectedOut->setNewEmailAddress('test2@mail');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
