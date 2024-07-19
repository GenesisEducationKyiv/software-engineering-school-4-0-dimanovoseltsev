<?php

namespace tests\unit\app\application\actions;

use app\application\actions\DeleteCustomer;
use app\application\forms\DeleteCustomerForm;
use app\application\services\CustomerService;
use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class
DeleteCustomerTest extends UnitTestCase
{
    private DeleteCustomer $action;
    private CustomerService|MockObject $customerService;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerService = $this->getCustomerServiceMock();
        $this->logger = $this->getLogServiceMock();
        $this->action = new DeleteCustomer($this->customerService, $this->logger);
    }

    /**
     */
    public function testExecute()
    {
        $email = 'mail@mail.com';
        $dto = new DeleteCustomerForm($email);

        $this->customerService->expects($this->once())
            ->method('delete')
            ->with($dto->getEmail())
            ->willReturn(1);

        $this->logger->expects(self::once())
            ->method('info')
            ->with(sprintf("Deleted %d records by email %s", 1, $email));

        $actual = $this->action->execute($dto);
        self::assertEquals(1, $actual);
    }
}

