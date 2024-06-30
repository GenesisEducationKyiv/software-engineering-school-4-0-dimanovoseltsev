<?php

namespace app\application\services;

use app\application\exceptions\RemoteServiceException;
use app\domain\entities\Currency;
use app\domain\repositories\CurrencyRepositoryInterface;
use Throwable;

class CurrencyService implements CurrencyServiceInterface
{
    /**
     * @param CurrencyRepositoryInterface $repository
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly CurrencyRepositoryInterface $repository,
        private readonly LogServiceInterface $logService,

    ) {
    }

    /**
     * @return Currency|null
     */
    public function getActual(): ?Currency
    {
        try {
            return $this->repository->findActual();
        } catch (Throwable $e) {
            $this->logService->log($e->getMessage());
            return null;
        }
    }
}
