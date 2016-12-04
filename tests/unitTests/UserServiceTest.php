<?php

namespace Tests\unitTests;

use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Repositories\UserAttributeRepository;
use App\Models\DAO\User;
use App\Models\DAO\UserAttribute;

class UserServiceTest extends \TestCase {
    private $userService;
    private $userRepositoryMock;
    private $userAttributeRepositoryMock;
    private $userModelMock;
    private $userAttributeModelMock;

    public function setUp() {
        parent::setUp();

        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->setMethods(
                [
                        'get',
                        'getUserAttributes',
                        'upsertUserAttributeValue'
                ])->getMock();
        $this->userAttributeRepositoryMock = $this->getMockBuilder(
                UserAttributeRepository::class)->setMethods(
                [
                        'getUserAttributeByName'
                ])->getMock();

        $this->userService = new UserService($this->userRepositoryMock,
            $this->userAttributeRepositoryMock);

        $this->userModelMock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->userAttributeModelMock = $this->createMock(UserAttribute::class);
    }

    public function testUpsertUserAttributeValue() {
        $userId = "5";
        $userAttributeValueRequest = [
                "firstName" => "testUser",
                "lastName" => "test_last"
        ];
        $userModel = $this->userModelMock;
        $userAttributeModel = $this->userAttributeModelMock;

        $this->userRepositoryMock->method('get')->with($userId)->willReturn(
                $userModel);
        $this->userRepositoryMock->method('getUserAttributes')->willReturn([ ]);
        $this->userAttributeRepositoryMock->method('getUserAttributeByName')->willReturn(
                $userAttributeModel);

        $this->userRepositoryMock->expects($this->once())->method('get')->with(
                $userId);
        $this->userAttributeRepositoryMock->expects($this->atMost(2))->method(
                'getUserAttributeByName')->with($this->isType('string'),
                $this->isType('boolean'));

        $this->userRepositoryMock->expects($this->once())->method(
                'upsertUserAttributeValue')->with($userModel,
                $this->isType('array'));

        $this->userService->upsertUserAttributeValue($userId,
                $userAttributeValueRequest);
    }

}