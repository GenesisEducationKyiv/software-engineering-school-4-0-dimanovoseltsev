<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\application\services\CustomerServiceInterface;
use app\domain\entities\Customer;

class RetrieveCustomerByEmail extends BaseAction implements RetrieveCustomerByEmailInterface
{
    /**
     * @param CustomerServiceInterface $service
     */
    public function __construct(
        private readonly CustomerServiceInterface $service,
    ) {
    }

    /**
     * @param string $email
     * @return Customer
     * @throws NotExistException
     */
    public function execute(string $email): Customer
    {
        $entity = $this->service->getByEmail($email);

        return $this->checkExit($entity);
    }
}
