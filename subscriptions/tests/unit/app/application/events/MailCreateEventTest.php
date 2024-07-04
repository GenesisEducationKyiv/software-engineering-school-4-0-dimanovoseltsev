<?php

namespace tests\unit\app\application\events;

use app\application\events\MailCreateEvent;
use tests\unit\UnitTestCase;

class MailCreateEventTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $subscription = $this->getSubscriptionEntity();
        $currency = $this->getCurrencyEntity();
        $event = new MailCreateEvent($currency, $subscription);
        $body = $event->getBody();

        self::assertEquals('mail.create', $event->getName());
        self::assertIsArray($body);

        $body['eventId'] = 'event1';

        self::assertEquals(
            [
                "eventId" => "event1",
                "eventType" => $event->getName(),
                "timestamp" => $event->getTimestamp(),
                "data" => [
                    "email" => "mail@mail.com",
                    "currency" => [
                        'iso3' => $currency->getIso3()->value(),
                        'rate' => $currency->getRate()->value(),
                        'updatedAt' => $currency->getUpdatedAt()->value(),
                    ]
                ]
            ],
            $body
        );
    }
}

