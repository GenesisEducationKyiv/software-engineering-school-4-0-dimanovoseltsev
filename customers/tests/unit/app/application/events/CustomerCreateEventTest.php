<?php

namespace tests\unit\app\application\events;

use app\application\events\CurrencyUpdateEvent;
use app\application\events\CustomerCreateEvent;
use tests\unit\UnitTestCase;

class CustomerCreateEventTest extends UnitTestCase
{
    public function testEvent()
    {
        $customer = $this->getCustomerEntity();
        $event = new CustomerCreateEvent($customer);
        $body = $event->getBody();

        self::assertEquals('customer.create', $event->getName());
        self::assertIsArray($body);

        $body['eventId'] = 'event1';

        self::assertEquals(
            [
                "eventId" => "event1",
                "eventType" => $event->getName(),
                "timestamp" => $event->getTimestamp(),
                "data" => $customer->toArray()
            ],
            $body
        );
    }
}

