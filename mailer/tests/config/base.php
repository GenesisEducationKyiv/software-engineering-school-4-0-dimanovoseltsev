<?php
/**
 * Application configuration shared by all applications and test types
 */


use tests\components\DummyQueue;
use tests\components\YiiMailer;
use yii\console\controllers\MigrateController;
use yii\db\Connection;

return [
    'id' => 'test',
    'language' => 'en',
    'controllerMap' => [
        'fixture' => [
            'class' => yii\faker\FixtureController::class,
            'fixtureDataPath' => '@tests/_data',
            'templatePath' => '@tests/fixtures',
            'namespace' => 'tests\fixtures',
        ],
        'migrate' => [
            'class' => MigrateController::class,
            'migrationTable' => '{{%migration_test}}',
        ],
    ],
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'tablePrefix' => (string)getenv('DB_TABLE_PREFIX') . 'test_',
            'charset' => 'utf8mb4',
            'enableSchemaCache' => false,
        ],
        'sendEmailQueue' => ['class' => DummyQueue::class],
        'sendEmailFailQueue' => ['class' => DummyQueue::class],
        'sendEmailQueueWorker' => ['class' => DummyQueue::class],
        'mailer' => [
            'class' => YiiMailer::class,
            'transport' => [
                'dsn' => getenv('MAILER_DSN'),
            ],
            'useFileTransport' => (bool)getenv('MAILER_DEBUG'),
            'viewPath' => '@themes/mails/views',
        ],
    ],
    'container' => [
        'definitions' => []
    ],
    'params' => [],
];

