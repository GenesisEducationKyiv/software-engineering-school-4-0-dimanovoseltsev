<?php

namespace app\subscriptions\domain\repositories;

use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\domain\entities\Subscription;

interface SubscriptionRepositoryInterface
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription;

    /**
     * @param Subscription $currency
     * @return Subscription
     */
    public function save(Subscription $currency): Subscription;

    /**
     * @param SearchSubscribersForMailingDto $dto
     * @return array
     */
    public function getNotSent(SearchSubscribersForMailingDto $dto): array;
}
