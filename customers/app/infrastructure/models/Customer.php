<?php

namespace app\infrastructure\models;

use yii\db\ActiveRecord;

/**
 * Class Customer.
 *
 *
 * @property int $id
 * @property string $email
 */
class Customer extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%customers}}';
    }

    /**
     * Returns subscriptions' dictionary repository.
     *
     * @return CustomerQuery
     */
    public static function find(): CustomerQuery
    {
        return new CustomerQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * @return array<int, mixed>
     */
    public function rules(): array
    {
        return [
            [['email',], 'required'],
            ['email', 'unique'],
            ['email', 'email'],
            [['id'], 'integer'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'id',
            'email',
        ];
    }
}
