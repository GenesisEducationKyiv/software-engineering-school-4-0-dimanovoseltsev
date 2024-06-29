<?php

namespace app\subscriptions\application\services;

use app\domain\entities\Currency;
use app\subscriptions\domain\entities\Subscription;

interface MailServiceInterface
{
    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @return bool
     */
    public function sendMail(Currency $currency, Subscription $subscription): bool;
}
