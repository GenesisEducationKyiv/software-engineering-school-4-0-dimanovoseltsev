<?php

namespace app\subscriptions\application\actions;

use app\currencies\domain\entities\Currency;

interface SendEmailInterface
{
    /**
     * @param Currency $currency
     * @param string $email
     * @return bool
     */
    public function execute(Currency $currency, string $email): bool;
}
