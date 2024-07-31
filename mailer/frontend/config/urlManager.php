<?php

use yii\web\UrlManager;

return [
    'class' => UrlManager::class,
    'enableStrictParsing' => false,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'GET /metrics' => 'metrics/index',

        [
            'class' => 'yii\web\UrlRule',
            'pattern' => 'doc/<action:\w+>',
            'route' => 'doc/<action>'
        ]
    ],
];
