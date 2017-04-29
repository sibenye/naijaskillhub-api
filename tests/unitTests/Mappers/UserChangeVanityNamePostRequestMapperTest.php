<?php
/**
 * @package Tests\unitTests\Mappers
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\UserChangeVanityNamePostRequestMapper;
use App\Models\Requests\UserChangeVanityNamePostRequest;

/**
 * UserChangeVanityNamePostRequestMapper Tests.
 *
 * @author silver.ibenye
 *
 */
class UserChangeVanityNamePostRequestMapperTest extends \TestCase
{
    /**
     * @var UserChangeVanityNamePostRequestMapper
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
        $this->mapper = new UserChangeVanityNamePostRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                'newVanityName' => 'test2'
        ];

        $expectedOut = new UserChangeVanityNamePostRequest();
        $expectedOut->setNewVanityName('test2');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
