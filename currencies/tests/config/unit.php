<?php

/**
 * Application configuration shared by all applications unit tests
 */
return yii\helpers\ArrayHelper::merge(
    require('base.php'),
    [
        'id' => 'test-unit',
        'basePath' => dirname(__DIR__),
    ]
);
