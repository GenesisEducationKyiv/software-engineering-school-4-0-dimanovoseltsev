<?php

return yii\helpers\ArrayHelper::merge(
    require(YII_APP_BASE_PATH . '/common/config/main.php'),
    require(YII_APP_BASE_PATH . '/console/config/main.php'),
    require('base.php'),
    [
        'id' => 'test-functional',
        'basePath' => dirname(__DIR__),
        'modules' => [
        ],
    ]
);
