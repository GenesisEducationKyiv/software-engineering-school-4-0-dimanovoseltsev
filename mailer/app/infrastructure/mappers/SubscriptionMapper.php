<?php

namespace app\infrastructure\mappers;

use app\domain\entities\Subscription as SubscriptionEntity;
use app\domain\valueObjects\Email;
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
            'email' => $entity->getEmail()->value(),
        ];
    }

    /**
     * @param array{"email": string|null} $value
     * @return SubscriptionEntity
     */
    public static function fromPrimitive(array $value): SubscriptionEntity
    {
        return new SubscriptionEntity(
            new Email($value['email'] ?? ''),
        );
    }
}
