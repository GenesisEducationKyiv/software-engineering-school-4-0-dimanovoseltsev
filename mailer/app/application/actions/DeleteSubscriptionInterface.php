<?php

namespace app\application\actions;

use app\application\dto\DeleteSubscriptionDto;

interface DeleteSubscriptionInterface
{
    /**
     * @param DeleteSubscriptionDto $dto
     * @return int
     */
    public function execute(DeleteSubscriptionDto $dto): int;
}
