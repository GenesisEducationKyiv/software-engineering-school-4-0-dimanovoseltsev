<?php


return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'name' => 'Currencies Api',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'components' => [
        'db' => require 'db.php',
        'cache' => [
            'class' => \yii\caching\MemCache::class,
            'useMemcached' => true,
            'keyPrefix' => 'currencies',
            'servers' => [
                [
                    'host' => getenv('MEMCACHE_HOST'),
                    'port' => getenv('MEMCACHE_PORT'),
                    'weight' => 100,
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => require 'di.php'
    ],
];
