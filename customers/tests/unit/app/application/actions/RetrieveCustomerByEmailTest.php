<?php

namespace tests\unit\app\application\actions;

use app\application\actions\RetrieveCustomerByEmail;
use app\application\exceptions\NotExistException;
use app\application\services\CustomerService;
use app\domain\entities\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class RetrieveCustomerByEmailTest extends UnitTestCase
{
    private RetrieveCustomerByEmail $action;
    private CustomerService|MockObject $customerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerService = $this->getCustomerServiceMock();
        $this->action = new RetrieveCustomerByEmail($this->customerService);
    }

    /**
     */
    public function testExecute()
    {
        $email = 'mail.com';
        $entity = $this->getCustomerEntity();

        $this->customerService->expects($this->once())
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
        self::expectExceptionMessage("Customer not found");

        $email = 'mail.com';

        $this->customerService->expects($this->once())
            ->method('getByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->action->execute($email);
    }
}

