<?php

namespace app\currencies\application\actions;

use app\application\actions\BaseAction;
use app\application\actions\RetrieveCurrencyByCodeInterface;
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
     * @param string $code
     * @return Currency
     * @throws NotExistException
     */
    public function execute(string $code): Currency
    {
        $entity = $this->service->getByCode($code);

        return $this->checkExit($entity);
    }
}
