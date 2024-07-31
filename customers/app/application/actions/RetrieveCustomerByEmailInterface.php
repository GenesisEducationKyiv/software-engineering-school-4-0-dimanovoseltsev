<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Customer;

interface RetrieveCustomerByEmailInterface
{
    /**
     * @param string $email
     * @return Customer
     * @throws NotExistException
     */
    public function execute(string $email): Customer;
}
