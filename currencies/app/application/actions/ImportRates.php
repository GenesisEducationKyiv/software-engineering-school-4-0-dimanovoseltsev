<?php

namespace app\application\actions;

use app\application\enums\CurrencyIso;
use app\application\exceptions\UnexpectedValueException;
use app\application\forms\CurrencyForm;
use app\application\services\RateServiceInterface;
use app\domain\entities\Currency;

class ImportRates extends BaseAction implements ImportRatesInterface
{
    /**
     * @param RateServiceInterface $rateService
     * @param CreateOrUpdateCurrencyInterface $createOrUpdateCurrency
     * @param CurrencyIso $sourceCurrency
     * @param CurrencyIso $targetCurrency
     */
    public function __construct(
        private readonly RateServiceInterface $rateService,
        private readonly CreateOrUpdateCurrencyInterface $createOrUpdateCurrency,
        private readonly CurrencyIso $sourceCurrency,
        private readonly CurrencyIso $targetCurrency
    ) {
    }

    /**
     * @return Currency[]
     * @throws UnexpectedValueException
     */
    public function execute(): array
    {
        $rateDto = $this->rateService->getRate($this->sourceCurrency->value, $this->targetCurrency->value);

        return [
            $this->createOrUpdateCurrency->execute(
                new CurrencyForm($rateDto->getCurrency(), $rateDto->getRoundedRate())
            )
        ];
    }
}