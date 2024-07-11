<?php

namespace tests\unit\app\application\mappers;

use app\application\dto\DeleteSubscriptionDto;
use app\application\dto\SaveCurrencyDto;
use app\application\dto\SaveSubscriptionDto;
use app\application\enums\CurrencyIso;
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

    public function testConvertCurrencyUpdateToDto()
    {
        $eventData = [
            'iso3' => CurrencyIso::USD->value,
            'rate' => 5.1,
            'updatedAt' => time(),
        ];

        $actual = $this->mapper->convertCurrencyUpdateToDto($eventData);
        self::assertInstanceOf(SaveCurrencyDto::class, $actual);
    }

    public function testConvertCurrencyUpdateToDtoNotValid()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $eventData = [
            'iso3' => '',
            'rate' => -1,
            'updatedAt' => -1,
        ];

        $actual = $this->mapper->convertCurrencyUpdateToDto($eventData);
    }


    public function testConvertSubscriptionCreateToDto()
    {
        $eventData = [
            'email' => "mail@mail.com",
        ];

        $actual = $this->mapper->convertSubscriptionCreateToDto($eventData);
        self::assertInstanceOf(SaveSubscriptionDto::class, $actual);
    }

    public function testConvertSubscriptionCreateToDtoNotValid()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $eventData = [
            'email' => "mail.com",
        ];

        $actual = $this->mapper->convertSubscriptionCreateToDto($eventData);
    }

    public function testConvertSubscriptionDeleteToDto()
    {
        $eventData = [
            'email' => "mail@mail.com",
        ];

        $actual = $this->mapper->convertSubscriptionDeleteToDto($eventData);
        self::assertInstanceOf(DeleteSubscriptionDto::class, $actual);
    }

    public function testConvertSubscriptionDeleteToDtoNotValid()
    {
        self::expectException(NotValidException::class);
        self::expectExceptionMessage("Validation Failed");

        $eventData = [
            'email' => "mail.com",
        ];

        $actual = $this->mapper->convertSubscriptionDeleteToDto($eventData);
    }
}

