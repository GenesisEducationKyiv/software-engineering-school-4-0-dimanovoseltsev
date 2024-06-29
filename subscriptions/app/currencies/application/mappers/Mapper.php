<?php

namespace app\currencies\application\mappers;

use app\application\dto\CreateCurrencyDto;
use app\domain\entities\Currency;
use app\domain\valueObjects\Id;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class Mapper
{
    /**
     * @param CreateCurrencyDto $dto
     * @return Currency
     */
    public static function fromCreateDto(CreateCurrencyDto $dto): Currency
    {
        return new Currency(
            new Id(null),
            new Iso3($dto->getCurrencyCode()),
            new Rate($dto->getRate()),
            new Timestamp($dto->getCreatedAt()),
            new Timestamp(null),
        );
    }
}
