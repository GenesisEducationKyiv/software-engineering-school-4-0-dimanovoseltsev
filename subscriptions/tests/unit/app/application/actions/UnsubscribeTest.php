<?php

namespace tests\unit\app\application\actions;

use app\application\actions\Unsubscribe;
use app\application\events\SubscriptionDeleteEvent;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotValidException;
use app\application\forms\UnsubscribeForm;
use app\application\services\SubscriptionService;
use app\infrastructure\adapters\EventBusRabbitMQ;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class UnsubscribeTest extends UnitTestCase
{
    private Unsubscribe $action;
    private SubscriptionService|MockObject $service;
    private EventBusRabbitMQ|MockObject $eventBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->eventBus = $this->getEventBusMock();
        $this->action = new Unsubscribe($this->service, $this->eventBus);
    }


    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecute()
    {
        $form = new UnsubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn($entity);

        $this->service->expects($this->once())
            ->method('delete')
            ->with($entity->getEmail()->value())
            ->willReturn(1);

        $this->eventBus->expects($this->once())
            ->method('publish')
            ->with(new SubscriptionDeleteEvent($entity));

        $actual = $this->action->execute($form);
        self::assertEquals(1, $actual);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteNotExists()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Subscription not exist");

        $form = new UnsubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn(null);

        $actual = $this->action->execute($form);
    }

    /**
     * @throws NotValidException
     */
    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);

        $form = new UnsubscribeForm("mail");

        $actual = $this->action->execute($form);
    }
}

