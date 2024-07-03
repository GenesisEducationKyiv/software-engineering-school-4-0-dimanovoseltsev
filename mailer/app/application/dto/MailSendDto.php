<?php

namespace app\application\dto;

use app\application\traits\TimestampTrait;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

class MailSendDto
{
    use TimestampTrait;

    /**
     * @param Currency $currency
     * @param Subscription $subscription
     */
    public function __construct(
        private Currency $currency,
        private Subscription $subscription,
    ) {
        $this->setCurrentTimestamp();
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }
}
