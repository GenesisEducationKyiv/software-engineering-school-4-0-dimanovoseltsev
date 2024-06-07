<?php

namespace app\services;

use app\dto\subscription\CreateDto;
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
     * @param CreateDto $dto
     * @return Subscription
     */
    public function create(CreateDto $dto): Subscription;

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
