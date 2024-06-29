<?php

namespace app\currencies\application\actions;

use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\services\RateServiceInterface;
use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\UnexpectedValueException;

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
