<?php

namespace app\subscriptions\application\services;

use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\domain\entities\Subscription;

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
     * @param SearchSubscribersForMailingDto $dto
     * @return Subscription[]
     */
    public function getNotSent(SearchSubscribersForMailingDto $dto): array;

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription;
}
