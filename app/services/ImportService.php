<?php

namespace app\services;

use app\models\Currency;
use app\services\providers\ProviderInterface;
use yii\base\InvalidCallException;

class ImportService implements ImportServiceInterface
{
    /**
     * CurrenciesService constructor.
     * @param ProviderInterface $currencyRateProvider
     * @param CurrenciesServiceInterface $currenciesService
     */
    public function __construct(
        private readonly ProviderInterface $currencyRateProvider,
        private readonly CurrenciesServiceInterface $currenciesService,
    ) {
    }

    /**
     * @return Currency[]
     */
    public function importRates(): array
    {
        $rates = $this->currencyRateProvider->getActualRates();

        if (empty($rates)) {
            throw new InvalidCallException('Currency rate provider return empty');
        }

        $currencies = [];
        foreach ($rates as $code => $rate) {
            $model = $this->currenciesService->findByCode($code);
            if ($model === null) {
                $currencies[] = $this->currenciesService->create(['iso3' => $code, 'rate' => $rate]);
            } else {
                $currencies[] = $this->currenciesService->update($model, ['rate' => $rate]);
            }
        }
        return $currencies;
    }
}
