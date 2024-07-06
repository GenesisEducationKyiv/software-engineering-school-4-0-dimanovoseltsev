<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\mappers\CurrencyMapper;
use app\infrastructure\mappers\Mapper;
use app\infrastructure\mappers\SubscriptionMapper;
use app\infrastructure\models\Subscription as SubscriptionModel;
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

    /**
     * @param SearchSubscribersDto $dto
     * @return Subscription[]
     */
    public function findNotSent(SearchSubscribersDto $dto): array
    {
        /** @var SubscriptionModel[] $models */
        $models = $this->query->clear()
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
}
