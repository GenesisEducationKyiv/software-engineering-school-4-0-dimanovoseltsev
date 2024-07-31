<?php

namespace tests\unit\app\application\events;

use app\application\events\SubscriptionDeleteEvent;
use tests\unit\UnitTestCase;

class SubscriptionDeleteEventEventTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $subscription = $this->getSubscriptionEntity();
        $event = new SubscriptionDeleteEvent($subscription);
        $body = $event->getBody();

        self::assertEquals('subscription.delete', $event->getName());
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

