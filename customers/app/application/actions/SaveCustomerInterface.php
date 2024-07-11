<?php

namespace app\application\actions;

use app\application\dto\SaveCustomerDto;
use app\domain\entities\Customer;

interface SaveCustomerInterface
{
    /**
     * @param SaveCustomerDto $dto
     * @return Customer
     */
    public function execute(SaveCustomerDto $dto): Customer;
}
