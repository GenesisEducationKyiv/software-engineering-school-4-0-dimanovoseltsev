<?php

namespace app\application\mappers;

use app\application\dto\CreateSubscriptionDto;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;
use app\domain\valueObjects\Timestamp;

class Mapper
{
    /**
     * @param CreateSubscriptionDto $dto
     * @return Subscription
     */
    public static function fromCreateDto(CreateSubscriptionDto $dto): Subscription
    {
        return new Subscription(
            new Id(null),
            new Email($dto->getEmail()),
            new Timestamp($dto->getCreatedAt()),
            new Timestamp(null),
            new Timestamp(null),
        );
    }
}
