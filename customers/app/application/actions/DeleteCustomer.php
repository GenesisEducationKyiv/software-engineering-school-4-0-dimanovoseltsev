<?php

namespace app\application\actions;

use app\application\dto\DeleteCustomerDto;
use app\application\services\CustomerServiceInterface;

class DeleteCustomer extends BaseAction implements DeleteCustomerInterface
{
    /**
     * @param CustomerServiceInterface $subscriptionService
     */
    public function __construct(
        private readonly CustomerServiceInterface $subscriptionService
    ) {
    }

    /**
     * @param DeleteCustomerDto $dto
     * @return int
     */
    public function execute(DeleteCustomerDto $dto): int
    {
        return $this->subscriptionService->delete($dto->getEmail());
    }
}
