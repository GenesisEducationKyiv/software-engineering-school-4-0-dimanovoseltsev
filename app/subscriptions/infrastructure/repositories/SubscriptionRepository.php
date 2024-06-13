<?php

namespace app\subscriptions\infrastructure\repositories;

use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\domain\repositories\SubscriptionRepositoryInterface;
use app\subscriptions\infrastructure\mappers\Mapper;
use app\subscriptions\infrastructure\models\SubscriptionQuery;
use yii\db\Exception;
use app\subscriptions\infrastructure\models\Subscription as SubscriptionModel;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @param SubscriptionQuery $query
     * @param int $breakBetweenSending
     */
    public function __construct(
        private readonly SubscriptionQuery $query,
        private readonly int $breakBetweenSending
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

    /**
     * @param SearchSubscribersForMailingDto $dto
     * @return Subscription[]
     */
    public function getNotSent(SearchSubscribersForMailingDto $dto): array
    {
        /** @var SubscriptionModel[] $models */
        $models = $this->query->clear()
            ->prepareNotSent($this->breakBetweenSending)
            ->andFilterWhere(['>', 'id', $dto->getLastId()])
            ->limit($dto->getLimit())
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $entities = [];

        foreach ($models as $model) {
            $entities[] = Mapper::toEntity($model);
        }

        return $entities;
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription
    {
        $model = $this->query->clear()
            ->prepareNotSent($this->breakBetweenSending)
            ->findByEmail($email);
        return $model === null ? null : Mapper::toEntity($model);
    }
}
