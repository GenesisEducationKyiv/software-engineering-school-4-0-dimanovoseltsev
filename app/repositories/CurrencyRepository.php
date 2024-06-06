<?php

namespace app\repositories;

use app\exceptions\EntityException;
use app\models\Currency;
use app\models\query\CurrencyQuery;

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
        if (!$model->save()) {
            throw new EntityException($model, 'Currency not saved');
        }
        return $model;
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
     * @param array $data
     * @return Currency
     * @throws EntityException
     */
    public function create(array $data = []): Currency
    {
        /** @var Currency */
        $model = $this->currencyQuery->createModel($data);
        return $this->save($model);
    }

    /**
     * @param Currency $model
     * @param array $data
     * @return Currency
     * @throws EntityException
     */
    public function update(Currency $model, array $data = []): Currency
    {
        $model->load($data, '');
        return $this->save($model);
    }
}
