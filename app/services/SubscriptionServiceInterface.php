<?php

namespace app\services;

use app\forms\SubscribeFrom;
use app\models\Subscription;

/**
 * Class SubscriptionServiceInterface
 *
 * @package app\services
 */
interface SubscriptionServiceInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmail(string $email): ?Subscription;

    /**
     * @param SubscribeFrom $from
     * @return Subscription
     */
    public function create(SubscribeFrom $from): Subscription;

    /**
     * @param Subscription $model
     * @return Subscription
     */
    public function updateLastSend(Subscription $model): Subscription;

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmailAndNotSend(string $email): ?Subscription;
}
