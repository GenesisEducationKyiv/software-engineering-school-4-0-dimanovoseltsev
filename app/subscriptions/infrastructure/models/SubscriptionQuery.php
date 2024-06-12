<?php

namespace app\subscriptions\infrastructure\models;

use app\shared\application\exceptions\NotValidException;
use app\shared\infrastructure\models\BaseQuery;
use yii\db\Exception;

class SubscriptionQuery extends BaseQuery
{
    /**
     * @param string $code
     * @return Subscription|null
     */
    public function findByEmail(string $code): ?Subscription
    {
        /** @var Subscription */
        return $this->findOne(['email' => $code]);
    }

    /**
     * @param array<string, mixed> $values
     * @throws NotValidException|Exception
     */
    public function save(array $values): Subscription
    {
        $id = $values['id'] ?? null;

        $model = $id === null
            ? $this->createModel($values)
            : $this->populateModel($values);

        if (!$model->save()) {
            throw new NotValidException($model->getErrors(), 'Subscription not saved');
        }
        /** @var Subscription */
        return $model;
    }
}
