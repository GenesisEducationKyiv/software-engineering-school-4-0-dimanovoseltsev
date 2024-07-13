<?php

namespace app\application\actions;

use app\application\dto\DeleteCustomerDto;
use app\application\forms\DeleteCustomerForm;

interface DeleteCustomerInterface
{
    /**
     * @param DeleteCustomerForm $form
     * @return int
     */
    public function execute(DeleteCustomerForm $form): int;
}
