<?php

namespace tests\unit\app\application\forms;

use app\application\forms\SaveCustomerForm;
use tests\unit\UnitTestCase;

class SaveCustomerFormTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $form = new SaveCustomerForm('mail1@mail.com');

        self::assertTrue($form->validate());
        self::assertIsString($form->getEmail());
        self::assertIsInt($form->getTimestamp());
        self::assertEmpty($form->getErrors());
    }

    /**
     */
    public function testEventNotValid()
    {
        $form = new SaveCustomerForm('mail1',);

        self::assertFalse($form->validate());
        self::assertEquals([
            'email' => ['Is not a valid email address'],
        ], $form->getErrors());
    }
}

