<?php

namespace app\services;

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
     * @param array $data
     * @return Currency
     */
    public function create(array $data = []): Currency
    {
        return $this->currencyRepository->create($data);
    }


    /**
     * @param Currency $model
     * @param array $data
     * @return Currency
     */
    public function update(Currency $model, array $data = []): Currency
    {
        return $this->currencyRepository->update($model, $data);
    }
}
