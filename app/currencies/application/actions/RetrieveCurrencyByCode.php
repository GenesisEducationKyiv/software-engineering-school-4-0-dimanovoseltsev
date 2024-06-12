<?php

namespace app\currencies\application\actions;

use app\currencies\application\services\CurrencyServiceInterface;
use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotExistException;

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
        $this->checkExit($entity);

        return $entity;
    }
}
