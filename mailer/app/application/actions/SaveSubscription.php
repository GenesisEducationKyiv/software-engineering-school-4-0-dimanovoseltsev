<?php

namespace app\application\actions;

use app\application\dto\SaveSubscriptionDto;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class SaveSubscription extends BaseAction implements SaveSubscriptionInterface
{
    /**
     * @param SubscriptionServiceInterface $subscriptionService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService,
    ) {
    }

    /**
     * @param SaveSubscriptionDto $dto
     * @return Subscription
     */
    public function execute(SaveSubscriptionDto $dto): Subscription
    {
        $entity = $this->subscriptionService->getByEmail($dto->getEmail());

        if ($entity !== null) {
            return $entity;
        }

        $entity = new Subscription(new Id(null), new Email($dto->getEmail()));

        return $this->subscriptionService->save($entity);
    }
}
