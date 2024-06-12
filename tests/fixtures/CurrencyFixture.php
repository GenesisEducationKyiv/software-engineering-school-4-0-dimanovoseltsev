<?php

namespace tests\fixtures;


use app\currencies\infrastructure\models\Currency;

class CurrencyFixture extends \yii\test\ActiveFixture
{
    public $modelClass = Currency::class;
}
