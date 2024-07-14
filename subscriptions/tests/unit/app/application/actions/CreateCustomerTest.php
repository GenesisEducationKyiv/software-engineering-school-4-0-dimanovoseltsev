<?php

namespace tests\unit\app\application\actions;

use app\application\actions\CreateCustomer;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\application\services\SubscriptionService;
use app\domain\entities\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CreateCustomerTest extends UnitTestCase
{
    private CreateCustomer $action;
    private SubscriptionService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getCustomerServiceMock();
        $this->action = new CreateCustomer($this->service);
    }

    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecute()
    {
        $form = new CreateCustomerForm("mail@mail.com");
        $entity = $this->getCustomerEntity();

        $this->service->expects($this->once())
            ->method('create')
            ->with($form->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($form);
        self::assertInstanceOf(Customer::class, $actual);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);

        $form = new CreateCustomerForm("mail");

        $actual = $this->action->execute($form);
    }
}

