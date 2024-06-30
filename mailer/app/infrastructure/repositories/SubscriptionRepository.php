<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\mappers\Mapper;
use app\infrastructure\models\Subscription as SubscriptionModel;
use app\infrastructure\models\SubscriptionQuery;
use yii\db\Exception;

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
    public function findByEmail(string $email): ?Subscription
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
     * @param SearchSubscribersDto $dto
     * @return Subscription[]
     */
    public function findNotSent(SearchSubscribersDto $dto): array
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
    public function findByEmailAndNotSend(string $email): ?Subscription
    {
        $model = $this->query->clear()
            ->prepareNotSent($this->breakBetweenSending)
            ->findByEmail($email);

        return $model === null ? null : Mapper::toEntity($model);
    }
}