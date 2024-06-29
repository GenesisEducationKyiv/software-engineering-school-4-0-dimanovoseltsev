<?php

namespace app\application\actions;

use app\application\dto\SendEmailDto;
use app\domain\entities\Currency;

interface SendEmailInterface
{
    /**
     * @param Currency $currency
     * @param SendEmailDto $dto
     * @return bool
     */
    public function execute(Currency $currency, SendEmailDto $dto): bool;
}
