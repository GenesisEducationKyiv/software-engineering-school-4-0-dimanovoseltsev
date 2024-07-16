<?php

namespace app\application\actions;

use app\application\dto\DeleteSubscriptionDto;
use app\application\services\SubscriptionServiceInterface;

class DeleteSubscription extends BaseAction implements DeleteSubscriptionInterface
{
    /**
     * @param SubscriptionServiceInterface $subscriptionService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService
    ) {
    }

    /**
     * @param DeleteSubscriptionDto $dto
     * @return int
     */
    public function execute(DeleteSubscriptionDto $dto): int
    {
        return $this->subscriptionService->delete($dto->getEmail());
    }
}
