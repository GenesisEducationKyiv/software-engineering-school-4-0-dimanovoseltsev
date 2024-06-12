<?php

use api\modules\v1\Module;
use yii\web\JsonParser;
use yii\web\User;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'user' => [
            'class' => User::class,
            'identityClass' => \common\models\User::class,
            'enableSession' => false,
        ],
        'urlManager' => require 'urlManager.php',
    ],
    'params' => $params,
    'container' => [
        'definitions' => []
    ],
];

