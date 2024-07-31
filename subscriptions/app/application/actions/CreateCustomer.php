<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\application\services\CustomerServiceInterface;
use app\domain\entities\Customer;

class CreateCustomer extends BaseAction implements CreateCustomerInterface
{
    /**
     * @param CustomerServiceInterface $service
     */
    public function __construct(
        private readonly CustomerServiceInterface $service,
    ) {
    }

    /**
     * @param CreateCustomerForm $form
     * @return Customer
     * @throws NotValidException
     */
    public function execute(CreateCustomerForm $form): Customer
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        return $this->service->create($form->getEmail());
    }
}
