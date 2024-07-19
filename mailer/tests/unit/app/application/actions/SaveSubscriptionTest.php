<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SaveSubscription;
use app\application\dto\SaveSubscriptionDto;
use app\application\services\SubscriptionService;
use app\domain\entities\Subscription;
use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
SaveSubscriptionTest extends UnitTestCase
{
    private SaveSubscription $action;
    private SubscriptionService|MockObject $subscriptionService;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getSubscriptionServiceMock();
        $this->logger = $this->getLogServiceMock();
        $this->action = new SaveSubscription($this->subscriptionService, $this->logger);
    }

    public function testExecute()
    {
        $dto = new SaveSubscriptionDto("mail@mail.com");

        $entity = $this->getSubscriptionEntity(['updatedAt' => time()]);

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($dto->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testExecuteCreate()
    {
        $dto = new SaveSubscriptionDto("mail@mail.com");

        $entity = $this->getSubscriptionEntity(['updatedAt' => time()]);

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($dto->getEmail())
            ->willReturn(null);

        $this->subscriptionService->expects($this->once())
            ->method('save')
            ->willReturn($entity);

        $this->logger->expects(self::once())
            ->method('debug')
            ->with(
                sprintf(
                    "Save subscription %s",
                    $entity->getEmail()->value(),
                )
            );

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Subscription::class, $actual);
    }
}

