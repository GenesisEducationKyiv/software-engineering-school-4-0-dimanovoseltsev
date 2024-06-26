<?php

namespace app\currencies\infrastructure\models;

use app\shared\application\exceptions\NotValidException;
use app\shared\infrastructure\models\BaseQuery;
use app\subscriptions\infrastructure\models\SubscriptionQuery;
use yii\db\Exception;

/**
 * @method SubscriptionQuery clear()
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
