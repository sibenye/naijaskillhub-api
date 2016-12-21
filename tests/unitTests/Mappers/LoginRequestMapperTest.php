<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserPostRequestMapper;
use App\Models\Requests\UserPostRequest;
use App\Mappers\LoginRequestMapper;
use App\Models\Requests\LoginRequest;

/**
 * LoginRequestMapper Tests
 *
 * @author silver.ibenye
 *
 */
class LoginRequestMapperTest extends \TestCase
{
    /**
     * @var LoginRequestMapper
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
        $this->mapper = new LoginRequestMapper();
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
                "password" => "testPassword"
        ];

        $expectedOut = new LoginRequest();
        $expectedOut->setEmailAddress("testEmail");
        $expectedOut->setCredentialType("credType");
        $expectedOut->setPassword("testPassword");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
