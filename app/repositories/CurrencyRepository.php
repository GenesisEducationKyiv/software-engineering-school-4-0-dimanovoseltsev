<?php

namespace app\repositories;

use app\dto\currency\CreateDto;
use app\dto\currency\UpdateDto;
use app\exceptions\EntityException;
use app\models\Currency;
use app\models\query\CurrencyQuery;
use Throwable;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * @param CurrencyQuery $currencyQuery
     */
    public function __construct(
        private readonly CurrencyQuery $currencyQuery,
    ) {
    }

    /**
     * @throws EntityException
     */
    private function save(Currency $model): Currency
    {
        try {
            if (!$model->save()) {
                throw new EntityException($model, 'Currency not saved');
            }
            return $model;
        } catch (Throwable $exception) {
            throw new EntityException($model, $exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getByCode(string $code): ?Currency
    {
        return $this->currencyQuery->clear()->findByCode($code);
    }

    /**
     * @param CreateDto $dto
     * @return Currency
     * @throws EntityException
     */
    public function create(CreateDto $dto): Currency
    {
        $model = $this->currencyQuery->createModel();
        $model->iso3 = $dto->getIso3();
        $model->rate = $dto->getRate();
        return $this->save($model);
    }

    /**
     * @param Currency $model
     * @param UpdateDto $dto
     * @return Currency
     * @throws EntityException
     */
    public function update(Currency $model, UpdateDto $dto): Currency
    {
        $model->rate = $dto->getRate();
        return $this->save($model);
    }
}
