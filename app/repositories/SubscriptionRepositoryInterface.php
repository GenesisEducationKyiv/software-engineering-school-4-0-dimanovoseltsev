<?php

namespace app\repositories;

use app\dto\subscription\CreateDto;
use app\models\Subscription;

interface SubscriptionRepositoryInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription;

    /**
     * @param CreateDto $dto
     * @return Subscription
     */
    public function create(CreateDto $dto): Subscription;

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription;

    /**
     * @param Subscription $model
     * @return Subscription
     */
    public function updateLastSend(Subscription $model): Subscription;
}
