<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\domain\entities\Customer;

interface CreateCustomerInterface
{
    /**
     * @param CreateCustomerForm $form
     * @return Customer
     * @throws NotValidException
     */
    public function execute(CreateCustomerForm $form): Customer;
}
