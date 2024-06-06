<?php

namespace app\models;

use app\models\query\SubscriptionQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Subscription.
 *
 * @package app\models
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
     * @inheritdoc
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
    public static function find()
    {
        return new SubscriptionQuery(get_called_class());
    }

    /**
     * @inheritdoc
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

    /**
     * @return void
     */
    public function changeLastSendAt(): void
    {
        $this->last_send_at = time();
    }
}
