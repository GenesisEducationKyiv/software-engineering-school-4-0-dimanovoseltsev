<?php

namespace app\application\actions;

use app\application\enums\CurrencyIso;
use app\application\exceptions\NotExistException;
use app\application\services\CurrencyServiceInterface;
use app\domain\entities\Currency;

class RetrieveCurrencyByCode extends BaseAction implements RetrieveCurrencyByCodeInterface
{
    /**
     * @param CurrencyServiceInterface $service
     */
    public function __construct(
        private readonly CurrencyServiceInterface $service,
    ) {
    }

    /**
     * @param CurrencyIso $currency
     * @return Currency
     * @throws NotExistException
     */
    public function execute(CurrencyIso $currency): Currency
    {
        $entity = $this->service->getByCode($currency->value);

        return $this->checkExit($entity);
    }
}
