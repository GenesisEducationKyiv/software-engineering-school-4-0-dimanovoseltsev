<?php

namespace app\application\services;

use app\domain\entities\Customer;
use app\domain\repositories\CustomerRepositoryInterface;

class CustomerService implements CustomerServiceInterface
{
    /**
     * @param CustomerRepositoryInterface $repository
     */
    public function __construct(
        private readonly CustomerRepositoryInterface $repository,
    ) {
    }

    /**
     * @param string $email
     * @return Customer
     */
    public function create(string $email): Customer
    {
        return $this->repository->create($email);
    }

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return $this->repository->delete($email);
    }
}
