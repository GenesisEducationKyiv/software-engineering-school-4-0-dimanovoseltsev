<?php

namespace app\application\services;

use app\application\dto\CreateSubscriptionDto;
use app\application\mappers\Mapper;
use app\domain\entities\Subscription;
use app\domain\repositories\SubscriptionRepositoryInterface;

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
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return $this->repository->deleteByEmail($email);
    }
}
