<?php

namespace app\subscriptions\infrastructure\repositories;

use app\shared\application\exceptions\NotValidException;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\domain\repositories\SubscriptionRepositoryInterface;
use app\subscriptions\infrastructure\mappers\Mapper;
use app\subscriptions\infrastructure\models\SubscriptionQuery;
use yii\db\Exception;


class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @param SubscriptionQuery $query
     */
    public function __construct(
        private readonly SubscriptionQuery $query,
    ) {
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription
    {
        $model = $this->query->findByEmail($email);
        return $model === null ? null : Mapper::toEntity($model);
    }

    /**
     * @param Subscription $currency
     * @return Subscription
     * @throws NotValidException
     * @throws Exception
     */
    public function save(Subscription $currency): Subscription
    {
        $values = Mapper::toModelAttributes($currency);
        return Mapper::toEntity($this->query->save($values));
    }
}
