<?php

namespace app\subscriptions\application\actions;

use app\currencies\domain\entities\Currency;

interface SendEmailsScheduledInterface
{
    /**
     * @param Currency $currency
     * @param int $breakBetweenSending
     * @return int
     */
    public function execute(Currency $currency, int $breakBetweenSending): int;
}
