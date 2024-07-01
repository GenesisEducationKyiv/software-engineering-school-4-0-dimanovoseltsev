<?php

namespace app\subscriptions\application\services;

use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\application\mappers\Mapper;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\domain\repositories\SubscriptionRepositoryInterface;

class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * @param SubscriptionRepositoryInterface $repository
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $repository,
    ) {
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmail(string $email): ?Subscription
    {
        return $this->repository->findByEmail($email);
    }

    /**
     * @param Subscription $entity
     * @return Subscription
     */
    public function save(Subscription $entity): Subscription
    {
        return $this->repository->save($entity);
    }

    /**
     * @param CreateSubscriptionDto $dto
     * @return Subscription
     */
    public function create(CreateSubscriptionDto $dto): Subscription
    {
        return $this->repository->save(Mapper::fromCreateDto($dto));
    }

    /**
     * @param SearchSubscribersForMailingDto $dto
     * @return Subscription[]
     */
    public function getNotSent(SearchSubscribersForMailingDto $dto): array
    {
        return $this->repository->findNotSent($dto);
    }

    /**
     * @param string $email
     * @return Subscription|null
     */
    public function getByEmailAndNotSend(string $email): ?Subscription
    {
        return $this->repository->findByEmailAndNotSend($email);
    }
}
