<?php

namespace app\services;

use app\dto\subscription\CreateDto;
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
     * @return Subscription|null
     */
    public function findByEmail(string $email): ?Subscription
    {
        return $this->subscriptionRepository->getByEmail($email);
    }

    /**
     * @param CreateDto $dto
     * @return Subscription
     */
    public function create(CreateDto $dto): Subscription
    {
        return $this->subscriptionRepository->create($dto);
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
     * @return Subscription|null
     */
    public function findByEmailAndNotSend(string $email): ?Subscription
    {
        return $this->subscriptionRepository->getByEmailAndNotSend($email);
    }
}
