<?php

namespace tests\unit\app\application\events;

use app\application\events\MailSentEvent;
use tests\unit\UnitTestCase;

class MailSentEventTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $subscription = $this->getSubscriptionEntity();
        $event = new MailSentEvent($subscription);
        $body = $event->getBody();

        self::assertEquals('mail.sent', $event->getName());
        self::assertIsArray($body);

        $body['eventId'] = 'event1';

        self::assertEquals(
            [
                "eventId" => "event1",
                "eventType" => $event->getName(),
                "timestamp" => $event->getTimestamp(),
                "data" => ["email" => "mail@mail.com", "timestamp" => $event->getTimestamp()]
            ],
            $body
        );
    }
}

