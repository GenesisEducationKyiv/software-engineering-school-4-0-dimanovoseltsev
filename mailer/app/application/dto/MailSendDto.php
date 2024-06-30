<?php

namespace app\application\dto;

use app\domain\entities\Currency;
use app\domain\entities\Subscription;

readonly class MailSendDto
{
    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @param int $timestamp
     */
    public function __construct(
        private Currency $currency,
        private Subscription $subscription,
        private int $timestamp,
    ) {
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

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
