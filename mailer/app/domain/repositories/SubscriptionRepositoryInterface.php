<?php

namespace app\domain\repositories;

use app\domain\dto\SearchSubscribersDto;
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

    /**
     * @param SearchSubscribersDto $dto
     * @return Subscription[]
     */
    public function findNotSent(SearchSubscribersDto $dto): array;
}
