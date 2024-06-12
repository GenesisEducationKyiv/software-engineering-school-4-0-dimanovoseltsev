<?php

namespace app\currencies\application\actions;

use app\currencies\application\services\ImportCurrencyServiceInterface;
use app\shared\application\exceptions\NotSupportedException;

class ImportRates extends BaseAction implements ImportRatesInterface
{
    /**
     * @param ImportCurrencyServiceInterface $service
     */
    public function __construct(
        private readonly ImportCurrencyServiceInterface $service,
    ) {
    }

    /**
     * @return array
     * @throws NotSupportedException
     */
    public function execute(): array
    {
        return $this->service->importRates();
    }
}
