<?php

namespace app\infrastructure\models;

use yii\db\ActiveRecord;

/**
 * Class Subscription.
 *
 *
 * @property int $id
 * @property string $email
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
