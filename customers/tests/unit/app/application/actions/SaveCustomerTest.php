<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SaveCustomer;
use app\application\dto\SaveCustomerDto;
use app\application\services\CustomerService;
use app\domain\entities\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
SaveCustomerTest extends UnitTestCase
{
    private SaveCustomer $action;
    private CustomerService|MockObject $subscriptionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->subscriptionService = $this->getCustomerServiceMock();
        $this->action = new SaveCustomer($this->subscriptionService);
    }

    public function testExecute()
    {
        $dto = new SaveCustomerDto("mail@mail.com");

        $entity = $this->getCustomerEntity();

        $this->subscriptionService->expects($this->once())
            ->method('getByEmail')
            ->with($dto->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testExecuteCreate()
    {
        $dto = new SaveCustomerDto("mail@mail.com");

        $entity = $this->getCustomerEntity();

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

