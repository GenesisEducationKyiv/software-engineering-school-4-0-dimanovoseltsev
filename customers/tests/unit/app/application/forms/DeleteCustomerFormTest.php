<?php

namespace tests\unit\app\application\forms;

use app\application\forms\DeleteCustomerForm;
use tests\unit\UnitTestCase;

class DeleteCustomerFormTest extends UnitTestCase
{
    /**
     */
    public function testEvent()
    {
        $form = new DeleteCustomerForm('mail1@mail.com');

        self::assertTrue($form->validate());
        self::assertIsString($form->getEmail());
        self::assertIsInt($form->getTimestamp());
        self::assertEmpty($form->getErrors());
    }

    /**
     */
    public function testEventNotValid()
    {
        $form = new DeleteCustomerForm('',);

        self::assertFalse($form->validate());
        self::assertEquals([
            'email' => ['Cannot be blank'],
        ], $form->getErrors());
    }
}

