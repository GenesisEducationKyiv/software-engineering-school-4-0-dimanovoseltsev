<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;

class RetrieveSubscriptionByEmail extends BaseAction implements RetrieveSubscriptionByEmailInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
    ) {
    }

    /**
     * @param string $email
     * @return Subscription
     * @throws NotExistException
     */
    public function execute(string $email): Subscription
    {
        $entity = $this->service->getByEmail($email);

        return $this->checkExitSubscription($entity);
    }
}
