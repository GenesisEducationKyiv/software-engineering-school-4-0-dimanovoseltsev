<?php

namespace app\infrastructure\mappers;

use app\domain\entities\Customer as CustomerEntity;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;
use app\infrastructure\models\Customer;

class CustomerMapper
{
    /**
     * @param Customer $model
     * @return CustomerEntity
     */
    public static function toEntity(Customer $model): CustomerEntity
    {
        return new CustomerEntity(
            new Id($model->id),
            new Email($model->email),
        );
    }

    /**
     * @param CustomerEntity $entity
     * @return array<string, mixed>
     */
    public static function toModelAttributes(CustomerEntity $entity): array
    {
        return [
            'id' => $entity->getId()->value(),
            'email' => $entity->getEmail()->value(),
        ];
    }

    /**
     * @param array{id?: int, email?: string} $value
     * @return CustomerEntity
     */
    public static function fromPrimitive(array $value): CustomerEntity
    {
        return new CustomerEntity(
            new Id($value['id'] ?? null),
            new Email($value['email'] ?? ''),
        );
    }
}
