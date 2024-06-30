<?php

namespace tests\unit\app\application\actions;

use app\application\actions\SendEmailsScheduled;
use app\application\dto\SearchSubscribersForMailingDto;
use app\application\events\CreateMailEvent;
use app\application\exceptions\NotExistException;
use app\application\services\MailService;
use app\application\services\PublisherService;
use app\application\services\SubscriptionService;
use app\infrastructure\adapters\EventBusRabbitMQ;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SendEmailsScheduledTest extends UnitTestCase
{
    private SendEmailsScheduled $action;
    private SubscriptionService|MockObject $service;
    private EventBusRabbitMQ|MockObject $eventBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->eventBus = $this->getEventBusMock();
        $this->action = new SendEmailsScheduled($this->service, $this->eventBus);
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
     * @throws NotExistException
     */
    public function testExecute()
    {
        $currency = $this->getCurrencyEntity();

        $mapInvoke = [
            'service.getNotSent' => ['params' => [], 'return' => []],
            'eventBus.publish' => ['params' => [], 'return' => []],
        ];

        $subscriptions = [
            $this->getSubscriptionEntity(['id' => 1]),
            $this->getSubscriptionEntity(['id' => 2]),
            $this->getSubscriptionEntity(['id' => 3]),
        ];

        $mapInvoke['service.getNotSent']['params'][] = [new SearchSubscribersForMailingDto(0, 50)];
        $mapInvoke['service.getNotSent']['return'][] = $subscriptions;

        $mapInvoke['service.getNotSent']['params'][] = [new SearchSubscribersForMailingDto(3, 50)];
        $mapInvoke['service.getNotSent']['return'][] = [];

        foreach ($subscriptions as $subscription) {
            $mapInvoke['eventBus.publish']['params'][] = [
                new CreateMailEvent($currency, $subscription)
            ];
        }

        $matcher = self::exactly(count($mapInvoke['service.getNotSent']['params']));
        $this->service->expects($matcher)
            ->method('getNotSent')
            ->willReturnCallback(
                $this->willReturnCallbackPrepare(
                    $matcher,
                    $mapInvoke['service.getNotSent']['params'],
                    $mapInvoke['service.getNotSent']['return'],
                )
            );

        $matcher = self::exactly(count($mapInvoke['eventBus.publish']['params']));
        $this->eventBus->expects($matcher)
            ->method('publish')
            ->willReturnCallback(
                $this->willReturnCallbackPrepare(
                    $matcher,
                    $mapInvoke['eventBus.publish']['params'],
                )
            );

        $actual = $this->action->execute($currency);
    }
}

