<?php

namespace app\application\services;

use app\domain\entities\Currency;
use app\domain\entities\Subscription;

interface PublisherServiceInterface
{
    /**
     * @param Subscription $subscription
     * @param Currency $currency
     * @return void
     */
    public function enqueueMessageForSending(Subscription $subscription, Currency $currency): void;
}
