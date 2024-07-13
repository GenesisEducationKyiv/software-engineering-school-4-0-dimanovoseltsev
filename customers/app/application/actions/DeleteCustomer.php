<?php

namespace app\application\actions;

use app\application\dto\DeleteCustomerDto;
use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
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
     * @param DeleteCustomerForm $form
     * @return int
     * @throws NotValidException
     */
    public function execute(DeleteCustomerForm $form): int
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        return $this->subscriptionService->delete($form->getEmail());
    }
}
