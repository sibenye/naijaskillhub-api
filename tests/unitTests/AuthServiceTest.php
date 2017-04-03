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
use App\Repositories\AccountTypeRepository;
use App\Models\DAO\AccountType;

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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $accountTypeMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $accountTypeRepositoryMock;

    /**
     *
     * {@inheritDoc}
     * @see \Laravel\Lumen\Testing\TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                        'get',
                        'getUserByEmailAddress',
                        'getUserWhere',
                        'getUserAttributes',
                        'upsertUserAttributeValue',
                        'createUser',
                        'update',
                        'getUserByAuthToken'
                ])
            ->getMock();

        $this->userAttributeRepositoryMock = $this->getMockBuilder(UserAttributeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getUserAttributeByName'
        ])
            ->getMock();

        $this->credentialTypeRepositoryMock = $this->getMockBuilder(CredentialTypeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getCredentialTypeByName'
        ])
            ->getMock();

        $this->accountTypeRepositoryMock = $this->getMockBuilder(AccountTypeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getAccountTypeByName'
        ])
            ->getMock();

        $this->cryptoUtilMock = $this->getMockBuilder(NSHCryptoUtil::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                        'hashThis',
                        'generateJWToken'
                ])
            ->getMock();

        $this->authService = new AuthService($this->userRepositoryMock,
            $this->credentialTypeRepositoryMock, $this->userAttributeRepositoryMock,
            $this->accountTypeRepositoryMock, $this->cryptoUtilMock);

        $this->credentialTypeMock = $this->getMockBuilder(CredentialType::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userAttributeModelMock = $this->getMockBuilder(UserAttribute::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->accountTypeMock = $this->getMockBuilder(AccountType::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $registerRequest->setAccountType('talent');

        $this->userRepositoryMock->method('getUserByEmailAddress')
            ->with($registerRequest->getEmailAddress())
            ->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')
            ->with($registerRequest->getPassword())
            ->willReturn('password');

        $this->cryptoUtilMock->method('generateJWToken')->willReturn('adklfjdldkf');

        $this->userRepositoryMock->method('getUserByAuthToken')
            ->with('adklfjdldkf')
            ->willReturn([ ]);

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')
            ->with($registerRequest->getCredentialType())
            ->willReturn($this->credentialTypeMock);

        $this->accountTypeRepositoryMock->method('getAccountTypeByName')
            ->with($registerRequest->getAccountType())
            ->willReturn($this->accountTypeMock);

        $this->userRepositoryMock->expects($this->once())
            ->method('getUserByEmailAddress')
            ->with($registerRequest->getEmailAddress());

        $this->credentialTypeRepositoryMock->expects($this->once())
            ->method('getCredentialTypeByName')
            ->with($registerRequest->getCredentialType());

        $this->accountTypeRepositoryMock->expects($this->once())
            ->method('getAccountTypeByName')
            ->with($registerRequest->getAccountType());

        $this->cryptoUtilMock->expects($this->once())
            ->method('generateJWToken');

        $this->userRepositoryMock->method('createUser')->willReturn($this->userModelMock);

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "accountType" => "talent",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id,
                'accountTypeId' => $this->accountTypeMock->id
        ];
        $this->userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->with($userDataRequest);

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
        $registerRequest->setAccountType('talent');

        $userModel = $this->userModelMock;
        $userAttributeModel = $this->userAttributeModelMock;

        $this->userRepositoryMock->method('getUserByEmailAddress')
            ->with($registerRequest->getEmailAddress())
            ->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')
            ->with($registerRequest->getPassword())
            ->willReturn('password');

        $this->cryptoUtilMock->method('generateJWToken')->willReturn('adklfjdldkf');

        $this->userRepositoryMock->method('getUserByAuthToken')
            ->with('adklfjdldkf')
            ->willReturn([ ]);

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')
            ->with($registerRequest->getCredentialType())
            ->willReturn($this->credentialTypeMock);

        $this->accountTypeRepositoryMock->method('getAccountTypeByName')
            ->with($registerRequest->getAccountType())
            ->willReturn($this->accountTypeMock);

        $this->userAttributeRepositoryMock->method('getUserAttributeByName')->willReturn(
                $userAttributeModel);

        $this->userRepositoryMock->expects($this->once())
            ->method('getUserByEmailAddress')
            ->with($registerRequest->getEmailAddress());

        $this->credentialTypeRepositoryMock->expects($this->once())
            ->method('getCredentialTypeByName')
            ->with($registerRequest->getCredentialType());

        $this->accountTypeRepositoryMock->expects($this->once())
            ->method('getAccountTypeByName')
            ->with($registerRequest->getAccountType());

        $this->cryptoUtilMock->expects($this->once())
            ->method('generateJWToken');

        $this->userAttributeRepositoryMock->expects($this->atMost(2))
            ->method('getUserAttributeByName')
            ->with($this->isType('string'), $this->isType('boolean'));

        $this->userRepositoryMock->method('createUser')->willReturn($userModel);

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "accountType" => "talent",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id,
                'accountTypeId' => $this->accountTypeMock->id
        ];
        $this->userRepositoryMock->expects($this->once())
            ->method('createUser')
            ->with($userDataRequest);

        $this->userRepositoryMock->expects($this->once())
            ->method('upsertUserAttributeValue')
            ->with($userModel, $this->isType('array'));

        $this->authService->register($registerRequest);
    }
}
