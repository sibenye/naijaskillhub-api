<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserResetPasswordPostRequestMapper;
use App\Models\Requests\UserResetPasswordPostRequest;

/**
 * UserResetPasswordPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserResetPasswordPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserResetPasswordPostRequestMapper
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
        $this->mapper = new UserResetPasswordPostRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "newPassword" => 'newPasswordTest',
                "resetToken" => 'testToken'
        ];

        $expectedOut = new UserResetPasswordPostRequest();
        $expectedOut->setNewPassword('newPasswordTest');
        $expectedOut->setResetToken("testToken");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
