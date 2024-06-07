<?php

namespace app\repositories;

use app\exceptions\EntityException;
use app\models\Currency;
use app\models\query\CurrencyQuery;
use Throwable;
use yii\db\Exception;

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
            throw new EntityException($model, $exception->getMessage());
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
     * @param array $data
     * @return Currency
     * @throws EntityException|Exception
     */
    public function create(array $data = []): Currency
    {
        $model = $this->currencyQuery->createModel($data);
        return $this->save($model);
    }

    /**
     * @param Currency $model
     * @param array $data
     * @return Currency
     * @throws EntityException
     * @throws Exception
     */
    public function update(Currency $model, array $data = []): Currency
    {
        $model->load($data, '');
        return $this->save($model);
    }
}
