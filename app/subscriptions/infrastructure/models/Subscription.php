<?php

namespace app\subscriptions\infrastructure\models;

use yii\db\ActiveRecord;

/**
 * Class Subscription.
 *
 *
 * @property int $id
 * @property string $email
 * @property float $rate
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_send_at
 */
class Subscription extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%subscriptions}}';
    }

    /**
     * Returns subscriptions' dictionary repository.
     *
     * @return SubscriptionQuery
     */
    public static function find(): SubscriptionQuery
    {
        return new SubscriptionQuery(get_called_class());
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
            [['created_at', 'updated_at', 'last_send_at'], 'integer'],
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
            'created_at',
            'updated_at',
            'last_send_at',
        ];
    }

}
