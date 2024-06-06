<?php

namespace app\repositories;

use app\exceptions\EntityException;
use app\models\Currency;
use app\models\query\SubscriptionQuery;
use app\models\Subscription;

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
        if (!$model->save()) {
            throw new EntityException($model, 'Subscription not saved');
        }
        return $model;
    }

    /**
     * @param string $email
     * @return Currency|null
     */
    public function getByEmail(string $email): ?Subscription
    {
        return $this->subscriptionQuery->clear()->findByEmail($email);
    }

    /**
     * @param array $data
     * @return Subscription
     * @throws EntityException
     */
    public function create(array $data): Subscription
    {
        /** @var Subscription */
        $model =  $this->subscriptionQuery->createModel($data);
        return $this->save($model);
    }

    /**
     * @param string $email
     * @return Currency|null
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
