<?php


use Interop\Amqp\AmqpTopic;
use yii\queue\Queue;
use yii\queue\serializers\JsonSerializer;

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
        'eventBusQueue' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'exchangeName' => getenv('RABBITMQ_EVENT_BUS_EXCHANGE'),
            'exchangeType' => AmqpTopic::TYPE_TOPIC,
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
        ],
    ],
    'container' => [
        'definitions' => require 'di.php'
    ],
];
