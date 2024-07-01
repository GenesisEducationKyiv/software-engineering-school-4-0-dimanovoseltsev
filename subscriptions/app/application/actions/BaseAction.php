<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

abstract class BaseAction
{
    /**
     * @throws NotExistException
     */
    protected function checkExitCurrency(?Currency $entity): Currency
    {
        if ($entity === null) {
            throw new NotExistException("Currency not exist");
        }

        return $entity;
    }

    /**
     * @throws NotExistException
     */
    protected function checkExit(?Subscription $entity): Subscription
    {
        if ($entity === null) {
            throw new NotExistException("Subscription not exist");
        }

        return $entity;
    }
}
