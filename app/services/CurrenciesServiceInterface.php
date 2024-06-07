<?php

namespace app\services;

use app\dto\currency\CreateDto;
use app\dto\currency\UpdateDto;
use app\models\Currency;

/**
 * Interface CurrenciesServiceInterface
 *
 * @package app\services
 */
interface CurrenciesServiceInterface
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency;

    /**
     * @param CreateDto $dto
     * @return Currency
     */
    public function create(CreateDto $dto): Currency;

    /**
     * @param Currency $model
     * @param UpdateDto $dto
     * @return Currency
     */
    public function update(Currency $model, UpdateDto $dto): Currency;
}
