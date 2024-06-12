<?php

namespace app\currencies\infrastructure\models;

use yii\db\ActiveRecord;


/**
 * Class Currency.
 *
 *
 * @property int $id
 * @property string $iso3
 * @property float $rate
 * @property int $created_at
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
            [['created_at', 'updated_at', 'id'], 'integer'],
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
            'created_at',
            'updated_at',
            'rate',
        ];
    }
}

