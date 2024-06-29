<?php

namespace app\currencies\application\services;

use app\application\dto\CreateCurrencyDto;
use app\application\mappers\Mapper;
use app\application\services\CurrencyServiceInterface;
use app\domain\entities\Currency;
use app\domain\repositories\CurrencyRepositoryInterface;

class CurrencyService implements CurrencyServiceInterface
{
    /**
     * @param CurrencyRepositoryInterface $repository
     */
    public function __construct(
        private readonly CurrencyRepositoryInterface $repository
    ) {
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency
    {
        return $this->repository->findByCode($code);
    }

    /**
     * @param Currency $entity
     * @return Currency
     */
    public function save(Currency $entity): Currency
    {
        return $this->repository->save($entity);
    }

    /**
     * @param CreateCurrencyDto $dto
     * @return Currency
     */
    public function create(CreateCurrencyDto $dto): Currency
    {
        return $this->repository->save(Mapper::fromCreateDto($dto));
    }
}
