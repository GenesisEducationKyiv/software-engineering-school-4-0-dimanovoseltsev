<?php

namespace tests\unit\app\application\services;


use app\application\dto\SearchSubscribersForMailingDto;
use app\application\services\SubscriptionService;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Subscription;
use app\infrastructure\repositories\SubscriptionRepository;
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
            ->onlyMethods(
                ['findByEmail', 'save', 'findNotSent', 'deleteByEmail']
            )
            ->getMock();
    }

    public function testGetByEmail()
    {
        $email = "mail@mail.com";

        $entity = $this->getSubscriptionEntity();
        $this->repository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($entity);

        $actual = $this->service->getByEmail($email);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testGetByEmailNull()
    {
        $email = "mail@mail.com";

        $this->repository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->service->getByEmail($email);
        self::assertNull($actual);
    }

    public function testGetNotSent()
    {
        $dto = new SearchSubscribersForMailingDto(0, 10);

        $entity = $this->getSubscriptionEntity();
        $this->repository->expects($this->once())
            ->method('findNotSent')
            ->with(new SearchSubscribersDto($dto->getLastId(), $dto->getLimit()))
            ->willReturn([$entity]);

        $actual = $this->service->getNotSent($dto);
        self::assertIsArray($actual);
        self::assertNotEmpty($actual);
    }

    public function testSave()
    {
        $entity = $this->getSubscriptionEntity();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($entity)
            ->willReturn($entity);

        $actual = $this->service->save($entity);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testDelete()
    {
        $email = "mail@mail.com";
        $this->repository->expects($this->once())
            ->method('deleteByEmail')
            ->with($email)
            ->willReturn(1);

        $actual = $this->service->delete($email);
        self::assertEquals(1, $actual);
    }
}

