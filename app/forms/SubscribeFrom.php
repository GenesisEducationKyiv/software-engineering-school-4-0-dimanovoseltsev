<?php

namespace app\forms;

use yii\base\Model;

class SubscribeFrom extends Model
{
    public ?string $email = null;

    /**
     * @return array
     * @return array<int, mixed>
     */
    public function rules(): array
    {
        return [
            [['email',], 'required'],
            [['email'], 'string'],
            [['email'], 'email'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'email',
        ];
    }
}
