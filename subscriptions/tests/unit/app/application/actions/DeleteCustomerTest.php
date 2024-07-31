<?php

namespace tests\unit\app\application\actions;

use app\application\actions\DeleteCustomer;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
use app\application\services\SubscriptionService;
use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class DeleteCustomerTest extends UnitTestCase
{
    private DeleteCustomer $action;
    private SubscriptionService|MockObject $service;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getCustomerServiceMock();
        $this->logger = $this->getLogServiceMock();
        $this->action = new DeleteCustomer($this->service, $this->logger);
    }

    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecute()
    {
        $form = new DeleteCustomerForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();

        $this->service->expects($this->once())
            ->method('delete')
            ->with($form->getEmail())
            ->willReturn(1);

        $this->logger->expects(self::once())
            ->method('info')
        ->with(sprintf("Deleted %d customers by email %s", 1, $form->getEmail()));

        $actual = $this->action->execute($form);
        self::assertTrue($actual);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);

        $form = new DeleteCustomerForm("mail");

        $actual = $this->action->execute($form);
    }
}

