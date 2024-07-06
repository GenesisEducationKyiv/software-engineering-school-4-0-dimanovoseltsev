<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;
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

    /**
     * @param Currency|null $entity
     * @return Subscription
     * @throws NotExistException
     */
    protected function checkExitCurrency(?Currency $entity): Currency
    {
        if ($entity === null) {
            throw new NotExistException("Currency not found");
        }

        return $entity;
    }
}
