<?php
/**
 * @package Tests\unitTests
 */
namespace Tests\unitTests;

use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\UserAttributeRepository;
use App\Models\DAO\User;
use App\Models\DAO\UserAttribute;
use App\Repositories\CategoryRepository;
use App\Repositories\CredentialTypeRepository;
use App\Models\DAO\CredentialType;
use App\Utilities\NSHCryptoUtil;
use App\Models\Requests\UserPostRequest;
use App\Services\AuthService;
use App\Models\Requests\RegisterRequest;

/**
 * AuthService Tests.
 * @author silver.ibenye
 *
 */
class AuthServiceTest extends \TestCase
{
    /**
     *
     * @var AuthService
     */
    private $authService;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepositoryMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $credentialTypeRepositoryMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userAttributeRepositoryMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $credentialTypeMock;
    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $cryptoUtilMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userModelMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userAttributeModelMock;

    /**
     *
     * {@inheritDoc}
     * @see \Laravel\Lumen\Testing\TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'get',
                        'getUserByEmailAddress',
                        'getUserAttributes',
                        'upsertUserAttributeValue',
                        'createUser',
                        'update',
                        'getUserByAuthToken'
                ])->getMock();

        $this->userAttributeRepositoryMock = $this->getMockBuilder(UserAttributeRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'getUserAttributeByName'
                ])->getMock();

        $this->credentialTypeRepositoryMock = $this->getMockBuilder(CredentialTypeRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'getCredentialTypeByName'
                ])->getMock();

        $this->cryptoUtilMock = $this->getMockBuilder(NSHCryptoUtil::class)->disableOriginalConstructor()->setMethods(
                [
                        'hashThis',
                        'secureRandomString'
                ])->getMock();

        $this->authService = new AuthService($this->userRepositoryMock,
            $this->credentialTypeRepositoryMock, $this->userAttributeRepositoryMock,
            $this->cryptoUtilMock);

        $this->credentialTypeMock = $this->getMockBuilder(CredentialType::class)->disableOriginalConstructor()->getMock();
        $this->userModelMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userAttributeModelMock = $this->getMockBuilder(UserAttribute::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * Test Register User.
     *
     * @return void
     */
    public function testRegister()
    {
        $registerRequest = new RegisterRequest();
        $registerRequest->setCredentialType("standard");
        $registerRequest->setEmailAddress("testUser@test.com");
        $registerRequest->setPassword("password");

        $this->userRepositoryMock->method('getUserByEmailAddress')->with(
                $registerRequest->getEmailAddress())->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')->with($registerRequest->getPassword())->willReturn(
                'password');

        $this->cryptoUtilMock->method('secureRandomString')->willReturn('adklfjdldkf');

        $this->userRepositoryMock->method('getUserByAuthToken')->with('adklfjdldkf')->willReturn(
                [ ]);

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')->with(
                $registerRequest->getCredentialType())->willReturn($this->credentialTypeMock);

        $this->userRepositoryMock->expects($this->once())->method('getUserByEmailAddress')->with(
                $registerRequest->getEmailAddress());

        $this->credentialTypeRepositoryMock->expects($this->once())->method(
                'getCredentialTypeByName')->with($registerRequest->getCredentialType());

        $this->cryptoUtilMock->expects($this->once())->method('secureRandomString');

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id,
                'authToken' => 'adklfjdldkf'
        ];
        $this->userRepositoryMock->expects($this->once())->method('createUser')->with(
                $userDataRequest);

        $this->authService->register($registerRequest);
    }

    /**
     * Test Register User.
     *
     * @return void
     */
    public function testRegisterWithFirstNameAndLastName()
    {
        $registerRequest = new RegisterRequest();
        $registerRequest->setCredentialType("standard");
        $registerRequest->setEmailAddress("testUser@test.com");
        $registerRequest->setPassword("password");
        $registerRequest->setFirstName("test");
        $registerRequest->setLastName("testUser");

        $userModel = $this->userModelMock;
        $userAttributeModel = $this->userAttributeModelMock;

        $this->userRepositoryMock->method('getUserByEmailAddress')->with(
                $registerRequest->getEmailAddress())->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')->with($registerRequest->getPassword())->willReturn(
                'password');

        $this->cryptoUtilMock->method('secureRandomString')->willReturn('adklfjdldkf');

        $this->userRepositoryMock->method('getUserByAuthToken')->with('adklfjdldkf')->willReturn(
                [ ]);

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')->with(
                $registerRequest->getCredentialType())->willReturn($this->credentialTypeMock);

        $this->userAttributeRepositoryMock->method('getUserAttributeByName')->willReturn(
                $userAttributeModel);

        $this->userRepositoryMock->expects($this->once())->method('getUserByEmailAddress')->with(
                $registerRequest->getEmailAddress());

        $this->credentialTypeRepositoryMock->expects($this->once())->method(
                'getCredentialTypeByName')->with($registerRequest->getCredentialType());

        $this->cryptoUtilMock->expects($this->once())->method('secureRandomString');

        $this->userAttributeRepositoryMock->expects($this->atMost(2))->method(
                'getUserAttributeByName')->with($this->isType('string'), $this->isType('boolean'));

        $this->userRepositoryMock->method('createUser')->willReturn($userModel);

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id,
                'authToken' => 'adklfjdldkf'
        ];
        $this->userRepositoryMock->expects($this->once())->method('createUser')->with(
                $userDataRequest);

        $this->userRepositoryMock->expects($this->once())->method('upsertUserAttributeValue')->with(
                $userModel, $this->isType('array'));

        $this->authService->register($registerRequest);
    }
}
