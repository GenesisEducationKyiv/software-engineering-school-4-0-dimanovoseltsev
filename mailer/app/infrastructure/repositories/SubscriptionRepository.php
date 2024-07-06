<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\mappers\CurrencyMapper;
use app\infrastructure\mappers\SubscriptionMapper;
use app\infrastructure\models\SubscriptionQuery;
use yii\db\Exception;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @param SubscriptionQuery $query
     */
    public function __construct(
        private readonly SubscriptionQuery $query
    ) {
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmail(string $email): ?Subscription
    {
        $model = $this->query->findByEmail($email);

        return $model === null ? null : SubscriptionMapper::toEntity($model);
    }

    /**
     * @param Subscription $subscription
     * @return Subscription
     * @throws NotValidException
     * @throws Exception
     */
    public function save(Subscription $subscription): Subscription
    {
        $values = CurrencyMapper::toModelAttributes($subscription);

        return SubscriptionMapper::toEntity($this->query->save($values));
    }

    /**
     * @param string $email
     * @return int
     */
    public function deleteByEmail(string $email): int
    {
        return $this->query->deleteByEmail($email);
    }
}
