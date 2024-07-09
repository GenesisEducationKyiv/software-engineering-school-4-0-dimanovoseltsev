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
        public Currency $currency,
        public Subscription $subscription,
        public int $timestamp,
    ) {
    }
}
