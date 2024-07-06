<?php

namespace app\domain\repositories;

use app\domain\entities\Subscription;

interface SubscriptionRepositoryInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmail(string $email): ?Subscription;

    /**
     * @param Subscription $subscription
     * @return Subscription
     */
    public function save(Subscription $subscription): Subscription;

    /**
     * @param string $email
     * @return int
     */
    public function deleteByEmail(string $email): int;
}
