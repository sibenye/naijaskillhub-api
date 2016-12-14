<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserPostRequestMapper;
use App\Models\Requests\UserPostRequest;

/**
 * UserPostRequestMapper Tests
 *
 * @author silver.ibenye
 *
 */
class UserPostRequestMapperTest extends \TestCase
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
        $this->mapper = new UserPostRequestMapper();
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

        $expectedOut = new UserPostRequest();
        $expectedOut->setEmailAddress("testEmail");
        $expectedOut->setCredentialType("credType");
        $expectedOut->setPassword("testPassword");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
