<?php

namespace tests\unit\app\application\mappers;

use app\application\dto\MailSentDto;
use app\application\exceptions\NotValidException;
use app\application\mappers\EventMapper;
use tests\unit\UnitTestCase;

class EventMapperTest extends UnitTestCase
{
    private EventMapper $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->mapper = new EventMapper();
    }

    /**
     */
    public function testConvertMailSentToDto()
    {
        $eventData = [
            'email' => 'mail@mail.com'
        ];

        $actual = $this->mapper->convertMailSentToDto($eventData);
        self::assertInstanceOf(MailSentDto::class, $actual);
    }


    public function testConvertMailSentToDtoNotValidEmail()
    {
        self::expectException(NotValidException::class);

        $eventData = [
            'email' => 'mail'
        ];

        $actual = $this->mapper->convertMailSentToDto($eventData);
    }
}

