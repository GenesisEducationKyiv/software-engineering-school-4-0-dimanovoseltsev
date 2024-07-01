<?php


use yii\symfonymailer\Mailer as SymfonyMailer;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'name' => 'Currency Rates Api',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'components' => [
        'db' => require 'db.php',
        'cache' => [
            'class' => \yii\caching\MemCache::class,
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => getenv('MEMCACHE_HOST'),
                    'port' => getenv('MEMCACHE_PORT'),
                    'weight' => 100,
                ],
            ],
        ],
        'mailer' => [
            'class' => SymfonyMailer::class,
            'transport' => [
                'dsn' => getenv('MAILER_DSN'),
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => [getenv("SENDER_EMAIL") => getenv("SENDER_NAME")],
            ],
            'useFileTransport' => (bool)getenv('MAILER_DEBUG'),
        ],
    ],
    'container' => [
        'definitions' => array_merge(
            require 'di/shared.php',
            require 'di/currencies.php',
            require 'di/subscriptions.php',
        )
    ],
];
