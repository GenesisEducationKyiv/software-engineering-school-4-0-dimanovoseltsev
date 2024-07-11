<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Customer;
use app\domain\repositories\CustomerRepositoryInterface;
use app\infrastructure\mappers\CustomerMapper;
use app\infrastructure\models\CustomerQuery;
use yii\db\Exception;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @param CustomerQuery $query
     */
    public function __construct(
        private readonly CustomerQuery $query
    ) {
    }

    /**
     * @param string $email
     * @return Customer|null
     */
    public function findByEmail(string $email): ?Customer
    {
        $model = $this->query->findByEmail($email);

        return $model === null ? null : CustomerMapper::toEntity($model);
    }

    /**
     * @param Customer $subscription
     * @return Customer
     * @throws NotValidException
     * @throws Exception
     */
    public function save(Customer $subscription): Customer
    {
        $values = CustomerMapper::toModelAttributes($subscription);

        return CustomerMapper::toEntity($this->query->save($values));
    }

    /**
     * @param string $email
     * @return int
     */
    public function deleteByEmail(string $email): int
    {
        return $this->query->deleteByEmail($email);
    }
}
