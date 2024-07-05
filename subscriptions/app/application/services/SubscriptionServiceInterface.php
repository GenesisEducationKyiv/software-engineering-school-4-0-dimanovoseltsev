<?php

namespace app\application\services;

use app\application\dto\CreateSubscriptionDto;
use app\domain\entities\Subscription;

interface SubscriptionServiceInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription;

    /**
     * @param Subscription $entity
     * @return Subscription
     */
    public function save(Subscription $entity): Subscription;

    /**
     * @param CreateSubscriptionDto $dto
     * @return Subscription
     */
    public function create(CreateSubscriptionDto $dto): Subscription;

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int;
}
