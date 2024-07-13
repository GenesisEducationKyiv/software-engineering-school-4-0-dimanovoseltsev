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
        private readonly CustomerRepositoryInterface $repository
    ) {
    }

    /**
     * @param string $email
     * @return Customer|null
     */
    public function getByEmail(string $email): ?Customer
    {
        return $this->repository->findByEmail($email);
    }

    /**
     * @param Customer $entity
     * @return Customer
     */
    public function save(Customer $entity): Customer
    {
        return $this->repository->save($entity);
    }

    /**
     * @param string $email
     * @return int
     */
    public function delete(string $email): int
    {
        return $this->repository->deleteByEmail($email);
    }
}
