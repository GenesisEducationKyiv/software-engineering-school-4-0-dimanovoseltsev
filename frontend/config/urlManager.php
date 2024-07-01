<?php

use yii\web\UrlManager;

return [
    'class' => UrlManager::class,
    'enableStrictParsing' => false,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'GET /rate' => 'rates/rate',
        'POST subscribe' => 'rates/subscribe',

        'OPTIONS <opts:(.*)>' => 'rates/options',
        '' => 'rates/options',
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => 'doc/<action:\w+>',
            'route' => 'doc/<action>'
        ]
    ],
];
