<?php

namespace app\subscriptions\infrastructure\mappers;

use app\domain\valueObjects\Id;
use app\domain\valueObjects\Timestamp;
use app\subscriptions\domain\entities\Subscription as SubscriptionEntity;
use app\subscriptions\domain\valueObjects\Email;
use app\subscriptions\infrastructure\models\Subscription;

class Mapper
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
            new Timestamp($model->created_at),
            new Timestamp($model->updated_at),
            new Timestamp($model->last_send_at),
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
            'created_at' => $entity->getCreatedAt()->value(),
            'updated_at' => $entity->getUpdatedAt()->value(),
            'last_send_at' => $entity->getLastSendAt()->value(),
        ];
    }

    /**
     * @param array{
     *     "id": int|null,
     *     "email": string|null,
     *     "createdAt": int|null,
     *     "updatedAt": int|null,
     *     "lastSendAt": int|null} $value
     * @return SubscriptionEntity
     */
    public static function fromPrimitive(array $value): SubscriptionEntity
    {
        return new SubscriptionEntity(
            new Id($value['id'] ?? null),
            new Email($value['email'] ?? ''),
            new Timestamp($value['createdAt'] ?? null),
            new Timestamp($value['updatedAt'] ?? null),
            new Timestamp($value['lastSendAt'] ?? null),
        );
    }
}
