<?php
/**
 * Application configuration shared by all applications and test types
 */


use app\domain\repositories\CurrencyRepositoryInterface;
use app\infrastructure\repositories\CurrencyRepository;
use tests\components\CurrencyClient;
use tests\components\DummyQueue;
use yii\console\controllers\MigrateController;
use yii\db\Connection;
use yii\di\Container;

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
        'eventBusQueue' => ['class' => DummyQueue::class],
    ],
    'container' => [
        'definitions' => [
            CurrencyRepositoryInterface::class => function (Container $container) {
                return new CurrencyRepository(
                    new CurrencyClient(),
                );
            },
        ]
    ],
    'params' => [],
];

