<?php

namespace app\subscriptions\domain\repositories;

use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\domain\dto\SearchSubscribersDto;
use app\subscriptions\domain\entities\Subscription;

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
