<?php

namespace app\application\actions;

use app\application\dto\MailSentDto;
use app\application\exceptions\NotExistException;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Timestamp;

class MailSent extends BaseAction implements MailSentInterface
{
    /**
     * @param SubscriptionServiceInterface $subscriptionService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService,
    ) {
    }

    /**
     * @param MailSentDto $dto
     * @return Subscription
     * @throws NotExistException
     */
    public function execute(MailSentDto $dto): Subscription
    {
        $subscription = $this->subscriptionService->getByEmailAndNotSend($dto->getEmail());
        $subscription = $this->checkExit($subscription);

        $subscription->setLastSendAt(new Timestamp($dto->getTimestamp()));
        return $this->subscriptionService->save($subscription);
    }
}
