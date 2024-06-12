<?php

namespace app\services;

use app\dto\currency\CreateDto;
use app\dto\currency\UpdateDto;
use app\enums\CurrencyIso;
use app\exceptions\NotSupportedException;
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
     * @throws NotSupportedException
     */
    public function importRates(): array
    {
        $rates = $this->currencyRateProvider->getActualRates();
        if (empty($rates)) {
            throw new InvalidCallException('Currency rate provider return empty');
        }

        $currencies = [];
        foreach ($rates as $code => $rate) {
            $currencyIso = CurrencyIso::tryFrom($code);
            if ($currencyIso === null) {
                throw new NotSupportedException(sprintf("Currency %s is not supported", $code));
            }
            $model = $this->currenciesService->findByCode($currencyIso->value);
            if ($model === null) {
                $currencies[] = $this->currenciesService->create(new CreateDto($currencyIso, $rate));
            } else {
                $currencies[] = $this->currenciesService->update($model, new UpdateDto($rate));
            }
        }
        return $currencies;
    }
}
