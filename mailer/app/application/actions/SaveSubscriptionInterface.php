<?php

namespace app\application\actions;

use app\application\dto\SaveSubscriptionDto;
use app\domain\entities\Subscription;

interface SaveSubscriptionInterface
{
    /**
     * @param SaveSubscriptionDto $dto
     * @return Subscription
     */
    public function execute(SaveSubscriptionDto $dto): Subscription;
}
