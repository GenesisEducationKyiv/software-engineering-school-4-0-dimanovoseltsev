<?php

namespace app\infrastructure\models;

use yii\db\ActiveRecord;

/**
 * Class Currency.
 *
 *
 * @property int $id
 * @property string $iso3
 * @property float $rate
 * @property int $updated_at
 */
class Currency extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%currencies}}';
    }

    /**
     * Returns currencies' dictionary repository.
     *
     * @return CurrencyQuery
     */
    public static function find(): CurrencyQuery
    {
        return new CurrencyQuery(get_called_class());
    }

    /**
     * @return array
     * @return array<int, mixed>
     */
    public function rules(): array
    {
        return [
            [['iso3',], 'required'],
            ['iso3', 'unique'],
            ['iso3', 'match', 'pattern' => '/^[a-z]+$/i'],
            [['iso3'], 'string', 'length' => [0, 3]],
            [['rate',], 'number'],
            [['updated_at', 'id'], 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'iso3',
            'updated_at',
            'rate',
        ];
    }
}
