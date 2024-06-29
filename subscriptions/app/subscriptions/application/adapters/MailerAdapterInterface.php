<?php

namespace app\subscriptions\application\adapters;

use app\domain\entities\Currency;
use app\subscriptions\domain\entities\Subscription;

interface MailerAdapterInterface
{
    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @return bool
     */
    public function sendActualRate(Currency $currency, Subscription $subscription): bool;
}
