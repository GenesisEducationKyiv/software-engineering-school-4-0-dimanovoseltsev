<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\application\services\CurrencyServiceInterface;
use app\domain\entities\Currency;

class RetrieveActualCurrencyRate extends BaseAction implements RetrieveActualCurrencyRateInterface
{
    /**
     * @param CurrencyServiceInterface $service
     */
    public function __construct(
        private readonly CurrencyServiceInterface $service,
    ) {
    }

    /**
     * @return Currency
     * @throws NotExistException
     */
    public function execute(): Currency
    {
        $entity = $this->service->getActual();

        return $this->checkExitCurrency($entity);
    }
}
