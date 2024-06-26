<?php

namespace tests\unit\app\subscriptions\application\actions;

use app\shared\application\exceptions\AlreadyException;
use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\actions\Subscribe;
use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\application\forms\SubscribeForm;
use app\subscriptions\application\services\SubscriptionService;
use app\subscriptions\domain\entities\Subscription;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SubscribeTest extends UnitTestCase
{
    private Subscribe $action;
    private SubscriptionService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->action = new Subscribe($this->service);
    }


    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecute()
    {
        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn(null);

        $this->service->expects($this->once())
            ->method('create')
            ->with(
                new CreateSubscriptionDto(
                    $form->getEmail(),
                    $form->getTimestamp()
                )
            )
            ->willReturn($entity);

        $actual = $this->action->execute($form);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteAlreadyExists()
    {
        self::expectException(AlreadyException::class);
        self::expectExceptionMessage("Already subscribed");

        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($form);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);

        $form = new SubscribeForm("mail");

        $actual = $this->action->execute($form);
    }
}

