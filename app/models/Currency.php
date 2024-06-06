<?php

namespace app\models;

use app\models\query\CurrencyQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Currency.
 *
 * @package app\models
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
     * @inheritdoc
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
    public static function find()
    {
        return new CurrencyQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['iso3', ], 'required'],
            ['iso3', 'unique'],
            ['iso3', 'match', 'pattern' => '/^[a-z]+$/i'],
            [['iso3'], 'string', 'length' => [0, 3]],
            [['rate',], 'number'],
            [['created_at', 'updated_at'], 'integer'],
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

    /**
     * @return string[][]
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
}
