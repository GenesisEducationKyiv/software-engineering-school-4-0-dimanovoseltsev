<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\DeleteCustomerForm;
use app\application\services\CustomerServiceInterface;

class DeleteCustomer extends BaseAction implements DeleteCustomerInterface
{
    /**
     * @param CustomerServiceInterface $service
     */
    public function __construct(
        private readonly CustomerServiceInterface $service,
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
        return $deleted > 0;
    }
}
