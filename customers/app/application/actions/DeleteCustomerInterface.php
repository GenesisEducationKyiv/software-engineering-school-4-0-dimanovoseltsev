<?php

namespace app\application\actions;

use app\application\dto\DeleteCustomerDto;

interface DeleteCustomerInterface
{
    /**
     * @param DeleteCustomerDto $dto
     * @return int
     */
    public function execute(DeleteCustomerDto $dto): int;
}
