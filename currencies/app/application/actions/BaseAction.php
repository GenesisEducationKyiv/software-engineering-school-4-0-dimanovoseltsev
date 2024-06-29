<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;

abstract class BaseAction
{
    /**
     * @throws NotExistException
     */
    protected function checkExit(?Currency $entity): Currency
    {
        if ($entity === null) {
            throw new NotExistException("Currency not found");
        }

        return $entity;
    }
}
