<?php

namespace app\currencies\infrastructure\mappers;

use app\currencies\domain\entities\Currency as CurrencyEntity;
use app\currencies\domain\valueObjects\Iso3;
use app\currencies\domain\valueObjects\Rate;
use app\currencies\infrastructure\models\Currency;
use app\shared\domain\valueObjects\Id;
use app\shared\domain\valueObjects\Timestamp;

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
     * @param array{"id": int|null, "iso3": string|null, "rate": float|null, "createdAt": int|null, "updatedAt": int|null} $value
     * @return CurrencyEntity
     */
    public static function fromPrimitive(array $value): CurrencyEntity
    {
        return new CurrencyEntity(
            new Id($value['id'] ?? null),
            new Iso3($value['iso3'] ?? null),
            new Rate($value['rate'] ?? null),
            new Timestamp($value['createdAt'] ?? null),
            new Timestamp($value['updatedAt'] ?? null),
        );
    }
}
