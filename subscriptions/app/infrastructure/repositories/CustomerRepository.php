<?php

namespace app\infrastructure\repositories;

use app\domain\entities\Customer;
use app\domain\repositories\CustomerRepositoryInterface;
use app\infrastructure\mappers\CustomerMapper;

class CustomerRepository implements CustomerRepositoryInterface
{

    /**
     * @param string $email
     * @return Customer|null
     */
    public function create(string $email): Customer
    {
        return CustomerMapper::fromPrimitive([]);
    }

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return 0;
    }
}
