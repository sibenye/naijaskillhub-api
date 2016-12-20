<?php
/**
 * @package
 */
namespace Tests\unitTests\Mappers;

use App\Mappers\EmailTransactionalSendRequestMapper;
use App\Models\Requests\EmailTransactionalSendRequest;

/**
 * EmailTransactionalSendRequestMapper Test.
 *
 * @author silver.ibenye
 *
 */
class EmailTransactionalSendRequestMapperTest extends \TestCase
{
    /**
     * @var EmailTransactionalSendRequestMapper
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
        $this->mapper = new EmailTransactionalSendRequestMapper();
    }

    /**
     * Test Mapping.
     *
     * @return void
     */
    public function testMapping()
    {
        $in = [
                'from' => 'test@mail',
                'to' => 'test2@mail',
                'subject' => 'testSubject',
                'content' => 'testContent'
        ];

        $expectedOut = new EmailTransactionalSendRequest();
        $expectedOut->setFrom('test@mail');
        $expectedOut->setTo('test2@mail');
        $expectedOut->setSubject('testSubject');
        $expectedOut->setContent('testContent');

        $out = $this->mapper->map($in);

        $this->assertEquals($expectedOut, $out);
    }
}
