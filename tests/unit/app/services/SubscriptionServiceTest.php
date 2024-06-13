<?php

namespace tests\unit\app\services;

use app\dto\subscription\CreateDto;
use app\forms\SubscribeFrom;
use app\models\Subscription;
use app\repositories\SubscriptionRepository;
use app\services\SubscriptionService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SubscriptionServiceTest extends UnitTestCase
{
    private SubscriptionService $service;
    private SubscriptionRepository|MockObject $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getSubscriptionRepositoryMock();
        $this->service = new SubscriptionService($this->repository);
    }

    /**
     * @return SubscriptionRepository|MockObject
     */
    protected function getSubscriptionRepositoryMock(): SubscriptionRepository|MockObject
    {
        return $this->getMockBuilder(SubscriptionRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'getByEmail',
                'create',
                'getByEmailAndNotSend',
                'updateLastSend',
            ])
            ->getMock();
    }


    public function testFindByEmail()
    {
        $email = 'test@example.com';
        $subscription = $this->getSubscriptionModelMock();
        $this->repository->expects($this->once())
            ->method('getByEmail')
            ->with($email)
            ->willReturn($subscription);

        $result = $this->service->findByEmail($email);

        self::assertInstanceOf(Subscription::class, $result);
    }

    public function testCreate()
    {
        $dto = new CreateDto('test@example.com');

        $subscription = $this->getSubscriptionModelMock();
        $this->repository->expects($this->once())
            ->method('create')
            ->willReturn($subscription);

        $result = $this->service->create($dto);

        self::assertInstanceOf(Subscription::class, $result);
    }

    public function testUpdateLastSend()
    {
        $subscription = $this->getSubscriptionModelMock();
        $this->repository->expects($this->once())
            ->method('updateLastSend')
            ->with($subscription)
            ->willReturn($subscription);

        $result = $this->service->updateLastSend($subscription);
        self::assertInstanceOf(Subscription::class, $result);
    }

    public function testFindByEmailAndNotSend()
    {
        $email = 'test@example.com';
        $subscription = $this->getSubscriptionModelMock();
        $this->repository->expects($this->once())
            ->method('getByEmailAndNotSend')
            ->with($email)
            ->willReturn($subscription);

        $result = $this->service->findByEmailAndNotSend($email);

        self::assertInstanceOf(Subscription::class, $result);
    }
}

