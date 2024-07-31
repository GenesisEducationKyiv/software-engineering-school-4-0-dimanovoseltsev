<?php

namespace tests\unit\app\application\forms;

use app\application\forms\EventForm;
use tests\unit\UnitTestCase;

class EventFormTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $form = new EventForm(
            'event1',
            'currency.create',
            time(),
            [
                'id' => 1
            ]
        );

        self::assertTrue($form->validate());
        self::assertIsString($form->getEventId());
        self::assertIsString($form->getEventType());
        self::assertIsInt($form->getTimestamp());
        self::assertIsArray($form->getData());
        self::assertEmpty($form->getErrors());
    }

    /**
     */
    public function testEventNotValid()
    {
        $form = new EventForm(
            '',
            '',
            '',
            []
        );

        self::assertFalse($form->validate());
        self::assertEquals([
            'eventId' => ['Cannot be blank'],
            'eventType' => ['Cannot be blank'],
            'data' => ['Expected a non-empty value. Got: array'],
            'timestamp' => ['Cannot be blank'],
        ], $form->getErrors());
    }
}

