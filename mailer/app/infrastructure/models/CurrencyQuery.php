<?php

namespace app\infrastructure\models;

use app\application\exceptions\NotValidException;
use yii\db\Exception;

/**
 * @method CurrencyQuery clear()
 */
class CurrencyQuery extends BaseQuery
{
    /**
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency
    {
        /** @var Currency */
        return $this->findOne(['iso3' => $code]);
    }

    /**
     * @param array<string, mixed> $values
     * @throws NotValidException|Exception
     */
    public function save(array $values): Currency
    {
        $id = $values['id'] ?? null;

        $model = $id === null
            ? $this->createModel($values)
            : $this->populateModel($values);

        if (!$model->save()) {
            throw new NotValidException($model->getErrors(), 'Currency not saved');
        }
        /** @var Currency */
        return $model;
    }
}
