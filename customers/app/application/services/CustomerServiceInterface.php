<?php

namespace app\application\services;

use app\domain\entities\Customer;

interface CustomerServiceInterface
{
    /**
     * @param string $email
     * @return Customer|null
     */
    public function getByEmail(string $email): ?Customer;

    /**
     * @param Customer $entity
     * @return Customer
     */
    public function save(Customer $entity): Customer;

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int;
}
