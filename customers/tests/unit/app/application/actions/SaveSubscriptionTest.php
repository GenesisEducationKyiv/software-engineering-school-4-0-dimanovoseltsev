<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SaveSubscription;
use app\application\dto\SaveSubscriptionDto;
use app\application\services\CustomerService;
use app\domain\entities\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
SaveSubscriptionTest extends UnitTestCase
{
    private SaveSubscription $action;
    private CustomerService|MockObject $subscriptionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getSubscriptionServiceMock();
        $this->action = new SaveSubscription($this->subscriptionService);
    }

    public function testExecute()
    {
        $dto = new SaveSubscriptionDto("mail@mail.com");

        $entity = $this->getSubscriptionEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($dto->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testExecuteCreate()
    {
        $dto = new SaveSubscriptionDto("mail@mail.com");

        $entity = $this->getSubscriptionEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($dto->getEmail())
            ->willReturn(null);

        $this->subscriptionService->expects($this->once())
            ->method('save')
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Customer::class, $actual);
    }
}

