<?php

namespace tests\unit\app\subscriptions\application\actions;

use app\application\exceptions\NotExistException;
use app\subscriptions\application\actions\SendEmailsScheduled;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\application\services\MailService;
use app\subscriptions\application\services\PublisherService;
use app\subscriptions\application\services\SubscriptionService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SendEmailsScheduledTest extends UnitTestCase
{
    private SendEmailsScheduled $action;
    private SubscriptionService|MockObject $service;
    private PublisherService|MockObject $publisherService;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->publisherService = $this->getPublisherServiceMock();
        $this->action = new SendEmailsScheduled($this->service, $this->publisherService);
    }

    /**
     * @return MailService|MockObject
     */
    protected function getPublisherServiceMock(): PublisherService|MockObject
    {
        return $this->getMockBuilder(PublisherService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'enqueueMessageForSending',
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
        $currencyCode = $currency->getIso3()->value();

        $mapInvoke = [
            'service.getNotSent' => ['params' => [], 'return' => []],
            'publisherService.enqueueMessageForSending' => ['params' => [], 'return' => []],
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
            $mapInvoke['publisherService.enqueueMessageForSending']['params'][] = [
                $subscription->getEmail()->value(),
                $currencyCode
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

        $matcher = self::exactly(count($mapInvoke['publisherService.enqueueMessageForSending']['params']));
        $this->publisherService->expects($matcher)
            ->method('enqueueMessageForSending')
            ->willReturnCallback(
                $this->willReturnCallbackPrepare(
                    $matcher,
                    $mapInvoke['publisherService.enqueueMessageForSending']['params'],
                )
            );

        $actual = $this->action->execute($currency);
    }
}

