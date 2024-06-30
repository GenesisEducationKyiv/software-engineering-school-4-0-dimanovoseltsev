<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SendEmail;
use app\application\dto\MailSendDto;
use app\application\events\MailSentEvent;
use app\application\services\MailService;
use app\infrastructure\adapters\EventBusRabbitMQ;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SendEmailTest extends UnitTestCase
{
    private SendEmail $action;
    private EventBusRabbitMQ|MockObject $eventBus;
    private MailService|MockObject $mailService;

    public function setUp(): void
    {
        parent::setUp();
        $this->eventBus = $this->getEventBusMock();
        $this->mailService = $this->getMailServiceMock();
        $this->action = new SendEmail($this->mailService, $this->eventBus);
    }

    /**
     * @return EventBusRabbitMQ|MockObject
     */
    protected function getEventBusMock(): EventBusRabbitMQ|MockObject
    {
        return $this->getMockBuilder(EventBusRabbitMQ::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'publish',
                ]
            )
            ->getMock();
    }

    /**
     * @return MailService|MockObject
     */
    protected function getMailServiceMock(): MailService|MockObject
    {
        return $this->getMockBuilder(MailService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'sendMail',
                ]
            )
            ->getMock();
    }

    /**
     */
    public function testExecute()
    {
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity();

        $dto = new MailSendDto($currency, $subscription, time());

        $this->eventBus->expects($this->once())
            ->method('publish')
            ->with(new MailSentEvent($dto->getSubscription(), $dto->getTimestamp()));

        $this->mailService->expects(self::once())
            ->method('sendMail')
            ->with($currency, $subscription)
            ->willReturn(true);

        $actual = $this->action->execute($dto);
        self::assertTrue($actual);
    }
}

