<?php

namespace app\infrastructure\mappers;

use app\domain\entities\Customer as CustomerEntity;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class CustomerMapper
{
    /**
     * @param array{
     *     "id": int|null,
     *     "email": string|null} $value
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
