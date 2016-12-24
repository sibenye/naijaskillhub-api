<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\AddCredentialRequestMapper;
use App\Models\Requests\AddCredentialRequest;

/**
 * AddCredentialRequestMapper Tests
 *
 * @author silver.ibenye
 *
 */
class AddCredentialRequestMapperTest extends \TestCase
{
    /**
     * @var AddCredentialRequestMapper
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
        $this->mapper = new AddCredentialRequestMapper();
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
                "password" => "testPassword",
                "credentialType" => "credType"
        ];

        $expectedOut = new AddCredentialRequest();
        $expectedOut->setEmailAddress("testEmail");
        $expectedOut->setPassword("testPassword");
        $expectedOut->setCredentialType("credType");

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
