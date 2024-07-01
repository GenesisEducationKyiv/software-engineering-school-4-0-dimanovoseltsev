<?php

namespace app\currencies\application\actions;

use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotExistException;

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
