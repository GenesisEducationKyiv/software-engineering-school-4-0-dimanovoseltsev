<?php

namespace app\repositories;

use app\dto\subscription\CreateDto;
use app\exceptions\EntityException;
use app\models\query\SubscriptionQuery;
use app\models\Subscription;
use Throwable;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * @param SubscriptionQuery $subscriptionQuery
     */
    public function __construct(
        private readonly SubscriptionQuery $subscriptionQuery,
    ) {
    }

    /**
     * @throws EntityException
     */
    private function save(Subscription $model): Subscription
    {
        try {
            if (!$model->save()) {
                throw new EntityException($model, 'Subscription not saved');
            }
            return $model;
        } catch (Throwable $exception) {
            throw new EntityException($model, $exception->getMessage());
        }
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription
    {
        return $this->subscriptionQuery->clear()->findByEmail($email);
    }

    /**
     * @param CreateDto $dto
     * @return Subscription
     * @throws EntityException
     */
    public function create(CreateDto $dto): Subscription
    {
        $model = $this->subscriptionQuery->createModel();
        $model->email = $dto->getEmail();
        return $this->save($model);
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription
    {
        return $this->subscriptionQuery->clear()->prepareNotSent()->findByEmail($email);
    }

    /**
     * @param Subscription $model
     * @return Subscription
     * @throws EntityException
     */
    public function updateLastSend(Subscription $model): Subscription
    {
        $model->changeLastSendAt();
        return $this->save($model);
    }
}
