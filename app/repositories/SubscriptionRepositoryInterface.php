<?php

namespace app\repositories;

use app\models\Currency;
use app\models\Subscription;

interface SubscriptionRepositoryInterface
{
    /**
     * @param string $email
     * @return Currency|null
     */
    public function getByEmail(string $email): ?Subscription;

    /**
     * @param array $data
     * @return Subscription
     */
    public function create(array $data): Subscription;

    /**
     * @param string $email
     * @return Currency|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription;

    /**
     * @param Subscription $model
     * @return Subscription
     */
    public function updateLastSend(Subscription $model): Subscription;
}
