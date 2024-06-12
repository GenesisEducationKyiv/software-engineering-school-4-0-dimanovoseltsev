<?php

namespace app\subscriptions\application\actions;

use app\shared\application\exceptions\NotExistException;
use app\subscriptions\domain\entities\Subscription;

abstract class BaseAction
{
    /**
     * @throws NotExistException
     */
    protected function checkExit(?Subscription $entity): void
    {
        if ($entity === null) {
            throw new NotExistException("Subscription not found");
        }
    }
}
