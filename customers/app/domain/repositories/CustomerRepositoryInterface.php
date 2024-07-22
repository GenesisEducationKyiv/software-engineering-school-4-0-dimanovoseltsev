<?php

namespace app\domain\repositories;

use app\domain\entities\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @param string $email
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer;

    /**
     * @param Customer $subscription
     * @return Customer
     */
    public function save(Customer $subscription): Customer;

    /**
     * @param string $email
     * @return int
     */
    public function deleteByEmail(string $email): int;
}
