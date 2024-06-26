<?php

namespace app\currencies\application\services;

use app\currencies\application\dto\CreateCurrencyDto;
use app\currencies\domain\entities\Currency;

interface CurrencyServiceInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency;

    /**
     * @param Currency $entity
     * @return Currency
     */
    public function save(Currency $entity): Currency;

    /**
     * @param CreateCurrencyDto $dto
     * @return Currency
     */
    public function create(CreateCurrencyDto $dto): Currency;
}
