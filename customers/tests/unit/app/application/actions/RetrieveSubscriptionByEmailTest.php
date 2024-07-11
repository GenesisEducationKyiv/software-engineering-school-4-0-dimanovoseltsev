<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveSubscriptionByEmail;
use app\application\exceptions\NotExistException;
use app\application\services\CustomerService;
use app\domain\entities\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RetrieveSubscriptionByEmailTest extends UnitTestCase
{
    private RetrieveSubscriptionByEmail $action;
    private CustomerService|MockObject $subscriptionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getSubscriptionServiceMock();
        $this->action = new RetrieveSubscriptionByEmail($this->subscriptionService);
    }

    /**
     */
    public function testExecute()
    {
        $email = 'mail.com';
        $entity = $this->getSubscriptionEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($email)
            ->willReturn($entity);

        $actual = $this->action->execute($email);
        self::assertInstanceOf(Customer::class, $actual);
    }

    /**
     */
    public function testExecuteNotExist()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Subscription not found");

        $email = 'mail.com';

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->action->execute($email);
    }
}

