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
        public readonly Currency $currency,
        public readonly Subscription $subscription,
    ) {
        $this->setCurrentTimestamp();
    }
}
