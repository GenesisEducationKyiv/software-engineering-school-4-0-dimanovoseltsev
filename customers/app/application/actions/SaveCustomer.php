<?php

namespace app\application\actions;

use app\application\dto\SaveCustomerDto;
use app\application\services\CustomerServiceInterface;
use app\domain\entities\Customer;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class SaveCustomer extends BaseAction implements SaveCustomerInterface
{
    /**
     * @param CustomerServiceInterface $subscriptionService
     */
    public function __construct(
        private readonly CustomerServiceInterface $subscriptionService,
    ) {
    }

    /**
     * @param SaveCustomerDto $dto
     * @return Customer
     */
    public function execute(SaveCustomerDto $dto): Customer
    {
        $entity = $this->subscriptionService->getByEmail($dto->getEmail());

        if ($entity !== null) {
            return $entity;
        }

        $entity = new Customer(new Id(null), new Email($dto->getEmail()));

        return $this->subscriptionService->save($entity);
    }
}
