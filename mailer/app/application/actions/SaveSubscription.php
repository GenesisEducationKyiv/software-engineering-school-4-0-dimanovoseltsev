<?php

namespace app\application\actions;

use app\application\dto\SaveSubscriptionDto;
use app\application\services\LogServiceInterface;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class SaveSubscription extends BaseAction implements SaveSubscriptionInterface
{
    /**
     * @param SubscriptionServiceInterface $subscriptionService
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService,
        private readonly LogServiceInterface $logService,
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

        $this->logService->debug(
            sprintf(
                "Save subscription %s",
                $entity->getEmail()->value(),
            )
        );

        return $this->subscriptionService->save($entity);
    }
}
