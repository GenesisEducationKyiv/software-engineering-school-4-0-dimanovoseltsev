<?php

namespace app\infrastructure\mappers;

use app\domain\entities\Subscription as SubscriptionEntity;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;
use app\infrastructure\models\Subscription;

class SubscriptionMapper
{
    /**
     * @param Subscription $model
     * @return SubscriptionEntity
     */
    public static function toEntity(Subscription $model): SubscriptionEntity
    {
        return new SubscriptionEntity(
            new Id($model->id),
            new Email($model->email),
        );
    }

    /**
     * @param SubscriptionEntity $entity
     * @return array<string, mixed>
     */
    public static function toModelAttributes(SubscriptionEntity $entity): array
    {
        return [
            'id' => $entity->getId()->value(),
            'email' => $entity->getEmail()->value(),
        ];
    }

    /**
     * @param array{id?: int, email?: string} $value
     * @return SubscriptionEntity
     */
    public static function fromPrimitive(array $value): SubscriptionEntity
    {
        return new SubscriptionEntity(
            new Id($value['id'] ?? null),
            new Email($value['email'] ?? ''),
        );
    }
}
