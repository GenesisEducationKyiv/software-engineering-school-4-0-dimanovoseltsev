<?php

namespace tests\unit\app\application\events;

use app\application\events\SubscriptionCreateEvent;
use tests\unit\UnitTestCase;

class SubscriptionCreateEventTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $subscription = $this->getSubscriptionEntity();
        $event = new SubscriptionCreateEvent($subscription);
        $body = $event->getBody();

        self::assertEquals('subscription.create', $event->getName());
        self::assertIsArray($body);

        $body['eventId'] = 'event1';

        self::assertEquals(
            [
                "eventId" => "event1",
                "eventType" => $event->getName(),
                "timestamp" => $event->getTimestamp(),
                "data" => $subscription->toArray()
            ],
            $body
        );
    }
}

