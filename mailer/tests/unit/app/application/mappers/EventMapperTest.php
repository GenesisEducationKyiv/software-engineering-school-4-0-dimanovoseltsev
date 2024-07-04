<?php

namespace tests\unit\app\application\mappers;

use app\application\dto\MailSendDto;
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
    public function testConvertMailCreateToDto()
    {
        $eventData = [
            'currency' => [
                'iso3' => 'USD',
                'rate' => 1.0,
                'updatedAt' => time()
            ],
            'email' => 'mail@mail.com'
        ];

        $actual = $this->mapper->convertMailCreateToDto($eventData);
        self::assertInstanceOf(MailSendDto::class, $actual);
    }


    public function testConvertMailCreateToDtoNotValidEmail()
    {
        self::expectException(NotValidException::class);

        $eventData = [
            'email' => 'mail'
        ];

        $actual = $this->mapper->convertMailCreateToDto($eventData);
    }

    public function testConvertMailCreateToDtoNotValidCurrency()
    {
        self::expectException(NotValidException::class);

        $eventData = [
            'email' => 'mail@mail.com'
        ];

        $actual = $this->mapper->convertMailCreateToDto($eventData);
    }
}

