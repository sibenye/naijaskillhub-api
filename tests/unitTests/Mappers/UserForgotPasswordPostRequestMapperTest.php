<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserResetPasswordPostRequestMapper;
use App\Models\Requests\UserResetPasswordPostRequest;
use App\Mappers\UserForgotPasswordPostRequestMapper;
use App\Models\Requests\UserForgotPasswordPostRequest;

/**
 * UserForgotPasswordPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserForgotPasswordPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserForgotPasswordPostRequestMapper
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
        $this->mapper = new UserForgotPasswordPostRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "resetToken" => 'testToken',
                "emailAddress" => 'testEmail'
        ];

        $expectedOut = new UserForgotPasswordPostRequest();
        $expectedOut->setResetToken("testToken");
        $expectedOut->setEmailAddress('testEmail');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
