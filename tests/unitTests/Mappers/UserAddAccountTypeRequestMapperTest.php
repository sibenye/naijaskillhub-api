<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserAddAccountTypeRequestMapper;
use App\Mappers\UserChangeVanityNamePostRequestMapper;
use App\Models\Requests\UserAddAccountTypeRequest;

/**
 * UserChangeVanityNamePostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserAddAccountTypeRequestMapperTest extends \TestCase
{
    /**
     * @var UserAddAccountTypeRequestMapper
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
        $this->mapper = new UserAddAccountTypeRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                'accountType' => 'talent'
        ];

        $expectedOut = new UserAddAccountTypeRequest();
        $expectedOut->setAccountType('talent');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
