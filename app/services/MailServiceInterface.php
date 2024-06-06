<?php

namespace app\services;

use app\models\Currency;
use app\models\Subscription;

interface MailServiceInterface
{
    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @return bool
     */
    public function sendActualRate(Currency $currency, Subscription $subscription): bool;
}
