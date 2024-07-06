<?php

namespace app\application\services;

use app\domain\entities\Currency;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;

class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * @param SubscriptionRepositoryInterface $repository
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $repository
    ) {
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription
    {
        return $this->repository->findByEmail($email);
    }

    /**
     * @param Currency $entity
     * @return Subscription
     */
    public function save(Currency $entity): Subscription
    {
        return $this->repository->save($entity);
    }

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return $this->repository->deleteByEmail($email);
    }
}
