<?php

namespace Tests\unitTests;

use App\Repositories\UserAttributeRepository;
use App\Services\UserAttributeService;
use App\Models\Requests\UserAttributePostRequest;

class UserAttributeServiceTest extends \TestCase {
    private $userAttributeService;
    private $userAttributeRepositoryMock;
    private $userAttributePostRequest;

    public function setUp() {
        parent::setUp();

        $this->userAttributeRepositoryMock = $this->createMock(
                UserAttributeRepository::class);

        $this->userAttributeService = new UserAttributeService(
            $this->userAttributeRepositoryMock);
    }

    public function testCreateUserAttribute() {
        $this->userAttributePostRequest = $this->createUserAttributePostRequest();
        $this->userAttributeRepositoryMock->expects($this->once())->method(
                'getUserAttributeByName')->with(
                $this->userAttributePostRequest->getName());

        $modelAttributes = $this->userAttributePostRequest->buildModelAttributes();
        $this->userAttributeRepositoryMock->expects($this->once())->method(
                'create')->with($modelAttributes);

        $this->userAttributeService->createUserAttribute(
                $this->userAttributePostRequest);
    }

    public function testUpdateUserAttribute() {
        $this->userAttributePostRequest = $this->createUserAttributePostRequest();
        $this->userAttributePostRequest->setUserAttributeId(1);
        $this->userAttributeRepositoryMock->expects($this->once())->method(
                'getUserAttributeByName')->with(
                $this->userAttributePostRequest->getName());

        $this->userAttributeRepositoryMock->expects($this->once())->method(
                'get')->with(
                $this->userAttributePostRequest->getUserAttributeId());

        $modelAttributes = $this->userAttributePostRequest->buildModelAttributes();
        $this->userAttributeRepositoryMock->expects($this->once())->method(
                'update')->with(
                $this->userAttributePostRequest->getUserAttributeId(),
                $modelAttributes);

        $this->userAttributeService->updateUserAttribute(
                $this->userAttributePostRequest);
    }

    private function createUserAttributePostRequest() {
        return new UserAttributePostRequest(
            [
                    'name' => 'testAttr'
            ]);
    }

}