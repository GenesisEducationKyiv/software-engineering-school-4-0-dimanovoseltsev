<?php

namespace app\subscriptions\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;
use app\domain\valueObjects\Timestamp;
use app\subscriptions\application\dto\SendEmailDto;
use app\subscriptions\application\services\MailServiceInterface;
use app\subscriptions\application\services\SubscriptionServiceInterface;

class SendEmail extends BaseAction implements SendEmailInterface
{
    /**
     * @param SubscriptionServiceInterface $subscriptionService
     * @param MailServiceInterface $mailService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $subscriptionService,
        private readonly MailServiceInterface $mailService,
    ) {
    }

    /**
     * @param Currency $currency
     * @param SendEmailDto $dto
     * @return bool
     * @throws NotExistException
     */
    public function execute(Currency $currency, SendEmailDto $dto): bool
    {
        $subscription = $this->subscriptionService->getByEmailAndNotSend($dto->getEmail());
        if ($subscription === null) {
            throw new NotExistException("Subscription not exit");
        }

        $state = $this->mailService->sendMail($currency, $subscription);
        if ($state) {
            $subscription->setLastSendAt(new Timestamp($dto->getTimestamp()));
            $this->subscriptionService->save($subscription);
        }

        return $state;
    }
}
