<?php

namespace tests\unit\app\application\services;

use app\application\enums\CurrencyIso;
use app\application\services\PublisherService;
use app\infrastructure\adapters\RabbitMq;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class PublisherServiceTest extends UnitTestCase
{
    private PublisherService $service;
    private RabbitMq|MockObject $rabbitMq;

    public function setUp(): void
    {
        parent::setUp();
        $this->rabbitMq = $this->getRabbitMqMock();
        $this->service = new PublisherService($this->rabbitMq);
    }

    /**
     * @return RabbitMq|MockObject
     */
    protected function getRabbitMqMock(): RabbitMq|MockObject
    {
        return $this->getMockBuilder(RabbitMq::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['sendMessage']
            )
            ->getMock();
    }

    public function testEnqueueMessageForSending()
    {
        $email = "mail@mail.com";
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity(['email' => $email]);

        $this->rabbitMq->expects($this->once())
            ->method('sendMessage')
            ->with(['email' => $email, 'currency' => $currency->toArray()]);
        $this->service->enqueueMessageForSending($subscription, $currency);
    }

}

