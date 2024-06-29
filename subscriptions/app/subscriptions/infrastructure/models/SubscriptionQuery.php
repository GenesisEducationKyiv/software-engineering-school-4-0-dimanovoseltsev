<?php

namespace app\subscriptions\infrastructure\models;

use app\application\exceptions\NotValidException;
use app\infrastructure\models\BaseQuery;
use yii\db\Exception;

/**
 * @method SubscriptionQuery clear()
 */
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

    /**
     * @param int $break
     * @return SubscriptionQuery
     */
    public function prepareNotSent(int $break): SubscriptionQuery
    {
        return $this->andWhere(['<=', 'last_send_at', time() - $break])
            ->orWhere(['IS', 'last_send_at', null]);
    }
}
