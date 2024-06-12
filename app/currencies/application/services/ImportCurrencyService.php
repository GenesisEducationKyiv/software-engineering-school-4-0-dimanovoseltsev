<?php

namespace app\currencies\application\services;

use app\currencies\application\actions\CreateOrUpdateCurrencyInterface;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\providers\ProviderInterface;
use app\currencies\domain\entities\Currency;
use yii\base\InvalidCallException;

class ImportCurrencyService implements ImportCurrencyServiceInterface
{
    /**
     * @param ProviderInterface $currencyRateProvider
     * @param CreateOrUpdateCurrencyInterface $createOrUpdateCurrency
     */
    public function __construct(
        private readonly ProviderInterface $currencyRateProvider,
        private readonly CreateOrUpdateCurrencyInterface $createOrUpdateCurrency,
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
        foreach ($rates as $dto) {
            $currencies[] = $this->createOrUpdateCurrency->execute(
                new CurrencyForm($dto->getCurrency(), $dto->getRate())
            );
        }
        return $currencies;
    }
}
