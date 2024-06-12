<?php

namespace app\subscriptions\application\mappers;

use app\shared\domain\valueObjects\Id;
use app\shared\domain\valueObjects\Timestamp;
use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\domain\valueObjects\Email;

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
