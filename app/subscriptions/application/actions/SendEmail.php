<?php

namespace app\subscriptions\application\actions;

use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotExistException;
use app\shared\domain\valueObjects\Timestamp;
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
     * @param string $email
     * @return bool
     * @throws NotExistException
     */
    public function execute(Currency $currency, string $email): bool
    {
        $subscription = $this->subscriptionService->findByEmailAndNotSend($email);
        if ($subscription === null) {
            throw new NotExistException("Subscription not exit");
        }

        $state = $this->mailService->sendMail($currency, $subscription);
        if ($state) {
            $subscription->setLastSendAt(new Timestamp(time())); // @todo as param
            $this->subscriptionService->save($subscription);
        }

        return $state;
    }
}
