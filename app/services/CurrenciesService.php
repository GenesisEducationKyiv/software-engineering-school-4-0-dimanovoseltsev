<?php

namespace app\services;

use app\dto\currency\CreateDto;
use app\dto\currency\UpdateDto;
use app\models\Currency;
use app\repositories\CurrencyRepositoryInterface;

/**
 * Class CurrenciesService
 *
 * @package app\services
 */
class CurrenciesService implements CurrenciesServiceInterface
{
    /**
     * CurrenciesService constructor.
     * @param CurrencyRepositoryInterface $currencyRepository
     */
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency
    {
        return $this->currencyRepository->getByCode($code);
    }

    /**
     * @param CreateDto $dto
     * @return Currency
     */
    public function create(CreateDto $dto): Currency
    {
        return $this->currencyRepository->create($dto);
    }


    /**
     * @param Currency $model
     * @param UpdateDto $dto
     * @return Currency
     */
    public function update(Currency $model, UpdateDto $dto): Currency
    {
        return $this->currencyRepository->update($model, $dto);
    }
}
