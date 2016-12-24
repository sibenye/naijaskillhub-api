<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\RegisterRequestMapper;
use App\Models\Requests\RegisterRequest;

/**
 * RegisterRequestMapper Tests
 *
 * @author silver.ibenye
 *
 */
class RegisterRequestMapperTest extends \TestCase
{
    /**
     * @var UserPostRequestMapper
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
        $this->mapper = new RegisterRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                "emailAddress" => "testEmail",
                "credentialType" => "credType",
                "password" => "testPassword",
                "firstName" => "test",
                "lastName" => "user"
        ];

        $expectedOut = new RegisterRequest();
        $expectedOut->setEmailAddress("testEmail");
        $expectedOut->setCredentialType("credType");
        $expectedOut->setPassword("testPassword");
        $expectedOut->setFirstName("test");
        $expectedOut->setLastName("user");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
