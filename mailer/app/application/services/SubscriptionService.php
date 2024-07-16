<?php

namespace app\application\services;

use app\application\dto\SearchSubscribersForMailingDto;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;

class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * @param SubscriptionRepositoryInterface $repository
     */
    public function __construct(
        private readonly SubscriptionRepositoryInterface $repository
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
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return $this->repository->deleteByEmail($email);
    }

    /**
     * @param SearchSubscribersForMailingDto $dto
     * @return Subscription[]
     */
    public function getNotSent(SearchSubscribersForMailingDto $dto): array
    {
        return $this->repository->findNotSent(new SearchSubscribersDto($dto->getLastId(), $dto->getLimit()));
    }
}
