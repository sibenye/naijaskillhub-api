<?php
namespace Tests\unitTests;

use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\UserAttributeRepository;
use App\Models\DAO\User;
use App\Models\DAO\UserAttribute;
use App\Repositories\CategoryRepository;
use App\Repositories\CredentialTypeRepository;
use App\Models\DAO\CredentialType;
use App\Helpers\NSHCryptoUtil;

class UserServiceTest extends \TestCase
{
    private $userService;
    private $userRepositoryMock;
    private $userAttributeRepositoryMock;
    private $userModelMock;
    private $userAttributeModelMock;
    private $categoryRepositoryMock;
    private $credentialTypeRepositoryMock;
    private $credentialTypeMock;
    private $cryptoUtilMock;

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

        $this->userService = new UserService($this->userRepositoryMock,
            $this->userAttributeRepositoryMock, $this->categoryRepositoryMock,
            $this->credentialTypeRepositoryMock, $this->cryptoUtilMock);

        $this->userModelMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userAttributeModelMock = $this->getMockBuilder(UserAttribute::class)->disableOriginalConstructor()->getMock();
        $this->credentialTypeMock = $this->getMockBuilder(CredentialType::class)->disableOriginalConstructor()->getMock();
    }

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

    public function testRegisterUser()
    {
        $userRegisterRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "password" => "password"
        ];

        $this->userRepositoryMock->method('getUserByEmailAddress')->with(
                $userRegisterRequest ['emailAddress'])->willReturn([ ]);

        $this->cryptoUtilMock->method('hashThis')->with($userRegisterRequest ['password'])->willReturn(
                'password');

        $this->credentialTypeRepositoryMock->method('getCredentialTypeByName')->with(
                $userRegisterRequest ['credentialType'])->willReturn($this->credentialTypeMock);

        $this->userRepositoryMock->expects($this->once())->method('getUserByEmailAddress')->with(
                $userRegisterRequest ['emailAddress']);

        $this->credentialTypeRepositoryMock->expects($this->once())->method(
                'getCredentialTypeByName')->with($userRegisterRequest ['credentialType']);

        $userDataRequest = [
                "emailAddress" => "testUser@test.com",
                "credentialType" => "standard",
                "password" => "password",
                "credentialTypeId" => $this->credentialTypeMock->id
        ];
        $this->userRepositoryMock->expects($this->once())->method('createUser')->with(
                $userDataRequest);

        $this->userService->registerUser($userRegisterRequest);
    }
}
