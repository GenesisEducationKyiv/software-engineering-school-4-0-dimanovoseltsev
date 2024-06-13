<?php

namespace app\currencies\application\actions;

use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\providers\ProviderInterface;
use app\currencies\domain\entities\Currency;
use yii\base\InvalidCallException;

class ImportRates extends BaseAction implements ImportRatesInterface
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
    public function execute(): array
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
