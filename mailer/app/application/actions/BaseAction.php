<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Subscription;

abstract class BaseAction
{
    /**
     * @param Subscription|null $entity
     * @return Subscription
     * @throws NotExistException
     */
    protected function checkExitSubscription(?Subscription $entity): Subscription
    {
        if ($entity === null) {
            throw new NotExistException("Subscription not found");
        }

        return $entity;
    }
}
