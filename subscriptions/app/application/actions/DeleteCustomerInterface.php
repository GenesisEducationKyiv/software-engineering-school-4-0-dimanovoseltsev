<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;

interface DeleteCustomerInterface
{
    /**
     * @param DeleteCustomerForm $form
     * @return bool
     * @throws NotValidException
     */
    public function execute(DeleteCustomerForm $form): bool;
}
