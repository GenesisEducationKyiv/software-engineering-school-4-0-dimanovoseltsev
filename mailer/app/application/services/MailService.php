<?php

namespace app\application\services;

use app\application\adapters\MailerAdapterInterface;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

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
