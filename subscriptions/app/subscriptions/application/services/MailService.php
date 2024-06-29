<?php

namespace app\subscriptions\application\services;

use app\domain\entities\Currency;
use app\subscriptions\application\adapters\MailerAdapterInterface;
use app\subscriptions\domain\entities\Subscription;

class MailService implements MailServiceInterface
{
    /**
     * @param MailerAdapterInterface $mailer
     */
    public function __construct(private readonly MailerAdapterInterface $mailer)
    {
    }

    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @return bool
     */
    public function sendMail(Currency $currency, Subscription $subscription): bool
    {
        return $this->mailer->sendActualRate($currency, $subscription);
    }
}
