<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\application\forms\SaveCustomerForm;
use app\application\services\CustomerServiceInterface;
use app\domain\entities\Customer;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class SaveCustomer extends BaseAction implements SaveCustomerInterface
{
    /**
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(
        private readonly CustomerServiceInterface $customerService,
    ) {
    }

    /**
     * @param SaveCustomerForm $form
     * @return Customer
     * @throws NotValidException
     */
    public function execute(SaveCustomerForm $form): Customer
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->customerService->getByEmail($form->getEmail());
        if ($entity !== null) {
            return $entity;
        }

        $entity = new Customer(new Id(null), new Email($form->getEmail()));

        return $this->customerService->save($entity);
    }
}
