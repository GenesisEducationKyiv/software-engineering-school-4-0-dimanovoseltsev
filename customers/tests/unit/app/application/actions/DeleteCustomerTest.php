<?php

namespace tests\unit\app\application\actions;

use app\application\actions\DeleteCustomer;
use app\application\dto\DeleteCustomerDto;
use app\application\services\CustomerService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
DeleteCustomerTest extends UnitTestCase
{
    private DeleteCustomer $action;
    private CustomerService|MockObject $customerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerService = $this->getCustomerServiceMock();
        $this->action = new DeleteCustomer($this->customerService);
    }

    /**
     */
    public function testExecute()
    {
        $email = 'mail@mail.com';
        $dto = new DeleteCustomerDto($email);

        $this->customerService->expects($this->once())
            ->method('delete')
            ->with($dto->getEmail())
            ->willReturn(1);

        $actual = $this->action->execute($dto);
        self::assertEquals(1, $actual);
    }
}

