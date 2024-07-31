<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
use app\application\services\CustomerServiceInterface;
use app\application\services\LogServiceInterface;

class DeleteCustomer extends BaseAction implements DeleteCustomerInterface
{
    /**
     * @param CustomerServiceInterface $customerService
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly CustomerServiceInterface $customerService,
        private readonly LogServiceInterface $logService,
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

        $deleted = $this->customerService->delete($form->getEmail());

        $this->logService->info(sprintf("Deleted %d records by email %s", $deleted, $form->getEmail()));

        return $deleted;
    }
}
