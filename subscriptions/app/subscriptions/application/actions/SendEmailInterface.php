<?php

namespace app\subscriptions\application\actions;

use app\currencies\domain\entities\Currency;
use app\subscriptions\application\dto\SendEmailDto;

interface SendEmailInterface
{
    /**
     * @param Currency $currency
     * @param SendEmailDto $dto
     * @return bool
     */
    public function execute(Currency $currency, SendEmailDto $dto): bool;
}
