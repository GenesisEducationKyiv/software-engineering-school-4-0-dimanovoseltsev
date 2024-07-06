<?php

namespace app\application\actions;

use app\application\dto\SaveSubscriptionDto;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;

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

        $entity = new Subscription(new Email($dto->getEmail()));

        return $this->subscriptionService->save($entity);
    }
}
