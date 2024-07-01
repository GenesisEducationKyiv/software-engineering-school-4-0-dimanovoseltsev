<?php

namespace app\application\actions;

use app\domain\entities\Currency;

interface SendEmailsScheduledInterface
{
    /**
     * @param Currency $currency
     * @return int
     */
    public function execute(Currency $currency): int;
}
