<?php

namespace tests\fixtures;


use app\subscriptions\infrastructure\models\Subscription;

class SubscriptionFixture extends \yii\test\ActiveFixture
{
    public $modelClass = Subscription::class;
}
