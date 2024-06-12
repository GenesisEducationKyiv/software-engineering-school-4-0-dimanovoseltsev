<?php

namespace app\subscriptions\application\services;

use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\application\mappers\Mapper;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\domain\repositories\SubscriptionRepositoryInterface;
use yii\db\Exception;

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
        return $this->repository->getByEmail($email);
    }

    /**
     * @param Subscription $entity
     * @return Subscription
     * @throws Exception
     * @throws NotValidException
     */
    public function save(Subscription $entity): Subscription
    {
        return $this->repository->save($entity);
    }

    /**
     * @param CreateSubscriptionDto $dto
     * @return Subscription
     * @throws NotValidException
     * @throws Exception
     */
    public function subscribe(CreateSubscriptionDto $dto): Subscription
    {
        return $this->repository->save(Mapper::fromCreateDto($dto));
    }
}