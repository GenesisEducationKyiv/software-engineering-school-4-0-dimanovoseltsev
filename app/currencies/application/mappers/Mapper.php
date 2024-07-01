<?php

namespace app\currencies\application\mappers;

use app\currencies\application\dto\CreateCurrencyDto;
use app\currencies\domain\entities\Currency;
use app\currencies\domain\valueObjects\Iso3;
use app\currencies\domain\valueObjects\Rate;
use app\shared\domain\valueObjects\Id;
use app\shared\domain\valueObjects\Timestamp;

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
