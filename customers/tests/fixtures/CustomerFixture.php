<?php

namespace tests\fixtures;


use app\infrastructure\models\Customer;

class CustomerFixture extends \yii\test\ActiveFixture
{
    public $modelClass = Customer::class;
}
