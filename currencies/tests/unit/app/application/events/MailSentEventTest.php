<?php

namespace tests\unit\app\application\events;

use app\application\events\CurrencyUpdateEvent;
use tests\unit\UnitTestCase;

class MailSentEventTest extends UnitTestCase
{
    public function testEvent()
    {
        $currency = $this->getCurrencyEntity();
        $event = new CurrencyUpdateEvent($currency);
        $body = $event->getBody();

        self::assertEquals('currency.update', $event->getName());
        self::assertIsArray($body);

        $body['eventId'] = 'event1';

        self::assertEquals(
            [
                "eventId" => "event1",
                "eventType" => $event->getName(),
                "timestamp" => $event->getTimestamp(),
                "data" => $currency->toArray()
            ],
            $body
        );
    }
}

