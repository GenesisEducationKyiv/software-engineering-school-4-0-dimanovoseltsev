<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
use app\application\services\CustomerServiceInterface;
use app\application\services\LogServiceInterface;

class DeleteCustomer extends BaseAction implements DeleteCustomerInterface
{
    /**
     * @param CustomerServiceInterface $service
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly CustomerServiceInterface $service,
        private readonly LogServiceInterface $logService,
    ) {
    }

    /**
     * @param DeleteCustomerForm $form
     * @return bool
     * @throws NotValidException
     */
    public function execute(DeleteCustomerForm $form): bool
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }
        $deleted = $this->service->delete($form->getEmail());

        $this->logService->info(sprintf("Deleted %d customers by email %s", $deleted, $form->getEmail()));

        return $deleted > 0;
    }
}
