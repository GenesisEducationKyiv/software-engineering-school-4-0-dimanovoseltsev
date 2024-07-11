<?php

namespace tests\fixtures;


use app\infrastructure\models\Customer;

class SubscriptionFixture extends \yii\test\ActiveFixture
{
    public $modelClass = Customer::class;
}
