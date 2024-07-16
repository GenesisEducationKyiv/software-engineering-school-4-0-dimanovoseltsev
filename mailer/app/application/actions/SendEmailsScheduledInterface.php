<?php

namespace app\application\actions;

use app\application\enums\CurrencyIso;

interface SendEmailsScheduledInterface
{
    /**
     * @param CurrencyIso $currency
     * @return int
     */
    public function execute(CurrencyIso $currency): int;
}
