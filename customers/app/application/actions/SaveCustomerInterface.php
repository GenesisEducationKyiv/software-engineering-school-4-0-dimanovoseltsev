<?php

namespace app\application\actions;

use app\application\dto\SaveCustomerDto;
use app\application\forms\SaveCustomerForm;
use app\domain\entities\Customer;

interface SaveCustomerInterface
{
    /**
     * @param SaveCustomerForm $form
     * @return Customer
     */
    public function execute(SaveCustomerForm $form): Customer;
}
