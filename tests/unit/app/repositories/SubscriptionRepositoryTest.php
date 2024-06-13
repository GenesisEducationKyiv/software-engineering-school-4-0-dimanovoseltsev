<?php

namespace tests\unit\app\repositories;

use app\dto\subscription\CreateDto;
use app\exceptions\EntityException;
use app\models\query\SubscriptionQuery;
use app\models\Subscription;
use app\repositories\SubscriptionRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SubscriptionRepositoryTest extends UnitTestCase
{
    private MockObject|SubscriptionQuery $subscriptionQueryMock;
    private SubscriptionRepository $subscriptionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subscriptionQueryMock = $this->getSubscriptionQuery();
        $this->subscriptionRepository = new SubscriptionRepository($this->subscriptionQueryMock);
    }

    /**
     * @return SubscriptionQuery|MockObject
     */
    protected function getSubscriptionQuery(): SubscriptionQuery|MockObject
    {
        return $this->getMockBuilder(SubscriptionQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'clear',
                'findByEmail',
                'createModel',
                'prepareNotSent',
            ])
            ->getMock();
    }

    public function testGetByEmail()
    {
        $email = 'test@example.com';
        $subscriptionModel = $this->getSubscriptionModelMock();

        $this->subscriptionQueryMock->expects(self::once())->method('clear')->willReturn($this->subscriptionQueryMock);
        $this->subscriptionQueryMock->expects(self::once())->method('findByEmail')->with($email)->willReturn(
            $subscriptionModel
        );

        $result = $this->subscriptionRepository->getByEmail($email);

        $this->assertInstanceOf(Subscription::class, $result);
    }

    public function testCreate()
    {
        $dto = new CreateDto('test@example.com');
        $subscriptionModel = $this->getSubscriptionModelMock(['email' => $dto->getEmail()]);

        $this->subscriptionQueryMock->expects(self::once())->method('createModel')->willReturn($subscriptionModel);
        $subscriptionModel->expects(self::once())->method('save')->willReturn(true);

        $result = $this->subscriptionRepository->create($dto);

        $this->assertInstanceOf(Subscription::class, $result);
    }

    public function testCreateFailure()
    {
        $dto = new CreateDto('test@example.com');

        $subscriptionModel = $this->getSubscriptionModelMock(['email' => $dto->getEmail()]);

        $this->subscriptionQueryMock->expects(self::once())->method('createModel')->willReturn(
            $subscriptionModel
        );
        $subscriptionModel->expects(self::once())->method('save')->willReturn(false);

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Subscription not saved');

        $this->subscriptionRepository->create($dto);
    }

    public function testGetByEmailAndNotSend()
    {
        $email = 'test@example.com';
        $subscriptionModel = $this->getSubscriptionModelMock();

        $this->subscriptionQueryMock->expects(self::once())->method('clear')->willReturn($this->subscriptionQueryMock);
        $this->subscriptionQueryMock->expects(self::once())->method('prepareNotSent')->willReturn(
            $this->subscriptionQueryMock
        );
        $this->subscriptionQueryMock->expects(self::once())->method('findByEmail')->with($email)->willReturn(
            $subscriptionModel
        );

        $result = $this->subscriptionRepository->getByEmailAndNotSend($email);

        $this->assertInstanceOf(Subscription::class, $result);
    }

    public function testUpdateLastSend()
    {
        $subscriptionModel = $this->getSubscriptionModelMock();

        $subscriptionModel->expects(self::once())->method('changeLastSendAt');
        $subscriptionModel->expects(self::once())->method('save')->willReturn(true);

        $result = $this->subscriptionRepository->updateLastSend($subscriptionModel);

        $this->assertInstanceOf(Subscription::class, $result);
    }

    public function testUpdateLastSendFailure()
    {
        $subscriptionModel = $this->getSubscriptionModelMock();

        $subscriptionModel->expects(self::once())->method('changeLastSendAt');
        $subscriptionModel->expects(self::once())->method('save')->willReturn(false);

        $this->expectException(EntityException::class);
        $this->expectExceptionMessage('Subscription not saved');

        $this->subscriptionRepository->updateLastSend($subscriptionModel);
    }
}

