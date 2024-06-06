<?php

namespace app\services;

use app\forms\SubscribeFrom;
use app\models\Currency;
use app\models\Subscription;
use app\repositories\SubscriptionRepositoryInterface;

/**
 * Class SubscriptionService
 * subscription
 *
 * @package app\services
 */
class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
    }

    /**
     * @param string $email
     * @return Currency|null
     */
    public function findByEmail(string $email): ?Subscription
    {
        return $this->subscriptionRepository->getByEmail($email);
    }

    /**
     * @param SubscribeFrom $from
     * @return Subscription
     */
    public function create(SubscribeFrom $from): Subscription
    {
        return $this->subscriptionRepository->create(['email' => $from->email]);
    }


    /**
     * @param Subscription $model
     * @return Subscription
     */
    public function updateLastSend(Subscription $model): Subscription
    {
        return $this->subscriptionRepository->updateLastSend($model);
    }

    /**
     * @param string $email
     * @return Currency|null
     */
    public function findByEmailAndNotSend(string $email): ?Subscription
    {
        return $this->subscriptionRepository->getByEmailAndNotSend($email);
    }
}
