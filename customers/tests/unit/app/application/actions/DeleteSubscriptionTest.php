<?php

namespace tests\unit\app\application\actions;

use app\application\actions\DeleteCustomer;
use app\application\dto\DeleteSubscriptionDto;
use app\application\services\CustomerService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
DeleteSubscriptionTest extends UnitTestCase
{
    private DeleteCustomer $action;
    private CustomerService|MockObject $subscriptionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getSubscriptionServiceMock();
        $this->action = new DeleteCustomer($this->subscriptionService);
    }

    /**
     */
    public function testExecute()
    {
        $email = 'mail@mail.com';
        $dto = new DeleteSubscriptionDto($email);

        $this->subscriptionService->expects($this->once())
            ->method('delete')
            ->with($dto->getEmail())
            ->willReturn(1);

        $actual = $this->action->execute($dto);
        self::assertEquals(1, $actual);
    }
}

