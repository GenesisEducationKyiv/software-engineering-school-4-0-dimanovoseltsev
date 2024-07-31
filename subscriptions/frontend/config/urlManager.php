<?php

use yii\web\UrlManager;

return [
    'class' => UrlManager::class,
    'enableStrictParsing' => false,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'POST /subscribe' => 'subscriptions/subscribe',
        'POST /unsubscribe' => 'subscriptions/unsubscribe',

        'GET /metrics' => 'metrics/index',

        'OPTIONS <opts:(.*)>' => 'subscriptions/options',
        '' => 'rates/options',
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => 'doc/<action:\w+>',
            'route' => 'doc/<action>'
        ]
    ],
];
