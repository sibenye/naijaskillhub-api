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

/**
 * UserService Tests.
 * @author silver.ibenye
 *
 */
class UserServiceTest extends \TestCase
{
    /**
     *
     * @var UserService
     */
    private $userService;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepositoryMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userAttributeRepositoryMock;

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
    private $categoryRepositoryMock;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $credentialTypeRepositoryMock;

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
    private $authServiceMock;

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
                        'createUser'
                ])->getMock();
        $this->userAttributeRepositoryMock = $this->getMockBuilder(UserAttributeRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'getUserAttributeByName'
                ])->getMock();

        $this->categoryRepositoryMock = $this->getMockBuilder(CategoryRepository::class)->disableOriginalConstructor()->getMock();

        $this->credentialTypeRepositoryMock = $this->getMockBuilder(CredentialTypeRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'getCredentialTypeByName'
                ])->getMock();

        $this->cryptoUtilMock = $this->getMockBuilder(NSHCryptoUtil::class)->disableOriginalConstructor()->setMethods(
                [
                        'hashThis'
                ])->getMock();

        $this->authServiceMock = $this->getMockBuilder(AuthService::class)->disableOriginalConstructor()->setMethods(
                [
                        'generateAuthToken'
                ])->getMock();

        $this->userService = new UserService($this->userRepositoryMock,
            $this->userAttributeRepositoryMock, $this->categoryRepositoryMock,
            $this->credentialTypeRepositoryMock, $this->cryptoUtilMock, $this->authServiceMock);

        $this->userModelMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userAttributeModelMock = $this->getMockBuilder(UserAttribute::class)->disableOriginalConstructor()->getMock();
        $this->credentialTypeMock = $this->getMockBuilder(CredentialType::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * Test UpsertUserAttributeValue.
     *
     * @return void
     */
    public function testUpsertUserAttributeValue()
    {
        $userId = "5";
        $userAttributeValueRequest = [
                "firstName" => "testUser",
                "lastName" => "test_last"
        ];
        $userModel = $this->userModelMock;
        $userAttributeModel = $this->userAttributeModelMock;

        $this->userRepositoryMock->method('get')->with($userId)->willReturn($userModel);

        $this->userAttributeRepositoryMock->method('getUserAttributeByName')->willReturn(
                $userAttributeModel);

        $this->userRepositoryMock->expects($this->once())->method('get')->with($userId);
        $this->userAttributeRepositoryMock->expects($this->atMost(2))->method(
                'getUserAttributeByName')->with($this->isType('string'), $this->isType('boolean'));

        $this->userRepositoryMock->expects($this->once())->method('upsertUserAttributeValue')->with(
                $userModel, $this->isType('array'));

        $this->userService->upsertUserAttributeValue($userId, $userAttributeValueRequest);
    }

    /**
     * Test RegisterUser.
     *
     * @return void
     */
    public function testRegisterUser()
    {
        $userRegisterRequest = new UserPostRequest();
        $userRegisterRequest->setCredentialType("standard");
        $userRegisterRequest->setEmailAddress("testUser@test.com");
        $userRegisterRequest->setPassword("password");

        $this->userRepositoryMock->method('getUserByEmailAddress')->with(
                $userRegisterRequest->getEmailAddress())->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')->with($userRegisterRequest->getPassword())->willReturn(
                'password');

        $this->authServiceMock->method('generateAuthToken')->willReturn('adklfjdldkf');

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')->with(
                $userRegisterRequest->getCredentialType())->willReturn($this->credentialTypeMock);

        $this->userRepositoryMock->expects($this->once())->method('getUserByEmailAddress')->with(
                $userRegisterRequest->getEmailAddress());

        $this->credentialTypeRepositoryMock->expects($this->once())->method(
                'getCredentialTypeByName')->with($userRegisterRequest->getCredentialType());

        $this->authServiceMock->expects($this->once())->method('generateAuthToken');

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id,
                'authToken' => 'adklfjdldkf'
        ];
        $this->userRepositoryMock->expects($this->once())->method('createUser')->with(
                $userDataRequest);

        $this->userService->registerUser($userRegisterRequest);
    }
}
