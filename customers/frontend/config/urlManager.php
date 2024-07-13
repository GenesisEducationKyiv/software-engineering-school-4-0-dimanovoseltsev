<?php

use yii\web\UrlManager;
return [
    'class' => UrlManager::class,
    'enableStrictParsing' => false,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        'POST customer' => 'customers/create',
        'DELETE customer' => 'customers/delete',

        'OPTIONS <opts:(.*)>' => 'customers/options',
        '' => 'customers/options',
        [
            'class' => 'yii\web\UrlRule',
            'pattern' => 'doc/<action:\w+>',
            'route' => 'doc/<action>'
        ]
    ],
];
