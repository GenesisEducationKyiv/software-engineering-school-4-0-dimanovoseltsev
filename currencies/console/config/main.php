<?php

use console\workers\MailWorker;
use yii\console\controllers\MigrateController;
use yii\log\FileTarget;
use yii\queue\amqp_interop\Queue;
use yii\queue\serializers\JsonSerializer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php',
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'sendEmailQueueWorker'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationTable' => '{{%system_db_migration}}' //leave as it was before
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'sendEmailQueue' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'queueName' => getenv('RABBITMQ_SEND_EMAIL_QUEUE'),
            'exchangeName' => getenv('RABBITMQ_SEND_EMAIL_EXCHANGE'),
            'routingKey' => getenv('RABBITMQ_SEND_EMAIL_ROUTING_KEY'),
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
        ],
        'sendEmailFailQueue' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'queueName' => getenv('RABBITMQ_SEND_EMAIL_FAIL_QUEUE'),
            'exchangeName' => getenv('RABBITMQ_SEND_EMAIL_FAIL_EXCHANGE'),
            'routingKey' => getenv('RABBITMQ_SEND_EMAIL_FAIL_ROUTING_KEY'),
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
        ],
        'sendEmailQueueWorker' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'queueName' => getenv('RABBITMQ_SEND_EMAIL_QUEUE'),
            'exchangeName' => getenv('RABBITMQ_SEND_EMAIL_EXCHANGE'),
            'routingKey' => getenv('RABBITMQ_SEND_EMAIL_ROUTING_KEY'),
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
            'commandClass' => MailWorker::class,
            'heartbeat' => (int)getenv('RABBITMQ_DEFAULT_HEARTBEAT'),
        ],
    ],
    'params' => $params,

    'container' => [
        'definitions' => [

        ]
    ],
];
