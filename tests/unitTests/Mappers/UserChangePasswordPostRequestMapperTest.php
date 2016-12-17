<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserChangePasswordPostRequestMapper;
use App\Models\Requests\UserChangePasswordPostRequest;

/**
 * UserChangePasswordPostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserChangePasswordPostRequestMapperTest extends \TestCase
{
    /**
     * @var UserChangePasswordPostRequestMapper
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
        $this->mapper = new UserChangePasswordPostRequestMapper();
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
                "oldPassword" => 'testOldPassword'
        ];

        $expectedOut = new UserChangePasswordPostRequest();
        $expectedOut->setNewPassword('newPasswordTest');
        $expectedOut->setOldPassword("testOldPassword");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
