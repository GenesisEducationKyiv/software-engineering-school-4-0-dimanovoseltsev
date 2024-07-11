<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Customer;

abstract class BaseAction
{
    /**
     * @param Customer|null $entity
     * @return Customer
     * @throws NotExistException
     */
    protected function checkExit(?Customer $entity): Customer
    {
        if ($entity === null) {
            throw new NotExistException("Customer not found");
        }

        return $entity;
    }
}
