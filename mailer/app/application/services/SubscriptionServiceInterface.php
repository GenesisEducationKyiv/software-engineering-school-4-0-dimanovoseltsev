<?php

namespace app\application\services;

use app\domain\entities\Currency;
use app\domain\entities\Subscription;

interface SubscriptionServiceInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription;

    /**
     * @param Currency $entity
     * @return Subscription
     */
    public function save(Currency $entity): Subscription;

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int;
}
