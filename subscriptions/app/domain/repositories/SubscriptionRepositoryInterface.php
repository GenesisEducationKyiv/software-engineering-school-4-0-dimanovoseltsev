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
     * @param Subscription $currency
     * @return Subscription
     */
    public function save(Subscription $currency): Subscription;

    /**
     * @param SearchSubscribersDto $dto
     * @return Subscription[]
     */
    public function findNotSent(SearchSubscribersDto $dto): array;

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmailAndNotSend(string $email): ?Subscription;
}
