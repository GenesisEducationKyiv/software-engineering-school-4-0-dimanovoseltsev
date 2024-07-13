<?php

namespace app\domain\repositories;

use app\domain\entities\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @param string $email
     * @return Customer|null
     */
    public function create(string $email): Customer;

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int;
}
