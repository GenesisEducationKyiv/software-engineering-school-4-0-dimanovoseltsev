<?php

use console\workers\MailSentWorker;
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
    'bootstrap' => ['log', 'mailSentQueue'],
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
        'mailSentQueue' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'queueName' => getenv('RABBITMQ_SENT_MAIL_QUEUE'),
            'exchangeName' => getenv('RABBITMQ_SENT_MAIL_EXCHANGE'),
            'routingKey' => getenv('RABBITMQ_SENT_MAIL_ROUTING_KEY'),
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
            'commandClass' => MailSentWorker::class,
            'heartbeat' => (int)getenv('RABBITMQ_DEFAULT_HEARTBEAT'),
        ],
        'mailSentFailQueue' => [
            'class' => Queue::class,
            'host' => getenv('RABBITMQ_HOST'),
            'port' => getenv('RABBITMQ_PORT'),
            'user' => getenv('RABBITMQ_USER'),
            'password' => getenv('RABBITMQ_PASS'),
            'queueName' => getenv('RABBITMQ_SENT_MAIL_FAIL_QUEUE'),
            'exchangeName' => getenv('RABBITMQ_SENT_MAIL_FAIL_EXCHANGE'),
            'routingKey' => getenv('RABBITMQ_SENT_MAIL_FAIL_ROUTING_KEY'),
            'strictJobType' => false,
            'serializer' => JsonSerializer::class,
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => []
    ],
];
