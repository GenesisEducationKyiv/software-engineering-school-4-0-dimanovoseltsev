<?php

namespace app\infrastructure\mappers;

use app\domain\entities\Currency as CurrencyEntity;
use app\domain\valueObjects\Id;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;
use app\infrastructure\models\Currency;

class Mapper
{
    /**
     * @param Currency $model
     * @return CurrencyEntity
     */
    public static function toEntity(Currency $model): CurrencyEntity
    {
        return new CurrencyEntity(
            new Id((int)$model->id),
            new Iso3($model->iso3),
            new Rate((float)$model->rate),
            new Timestamp($model->created_at),
            new Timestamp($model->updated_at),
        );
    }

    /**
     * @param CurrencyEntity $entity
     * @return array<string, mixed>
     */
    public static function toModelAttributes(CurrencyEntity $entity): array
    {
        return [
            'id' => $entity->getId()->value(),
            'iso3' => $entity->getIso3()->value(),
            'rate' => $entity->getRate()->value(),
            'created_at' => $entity->getCreatedAt()->value(),
            'updated_at' => $entity->getUpdatedAt()->value(),
        ];
    }

    /**
     * @param array{
     *     "id": int|null,
     *     "iso3": string|null,
     *     "rate": float|null,
     *     "createdAt": int|null,
     *     "updatedAt": int|null
     * } $value
     * @return CurrencyEntity
     */
    public static function fromPrimitive(array $value): CurrencyEntity
    {
        return new CurrencyEntity(
            new Id($value['id'] ?? null),
            new Iso3($value['iso3'] ?? ''),
            new Rate($value['rate'] ?? 1.0),
            new Timestamp($value['createdAt'] ?? null),
            new Timestamp($value['updatedAt'] ?? null),
        );
    }
}
