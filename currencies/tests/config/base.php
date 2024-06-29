<?php
/**
 * Application configuration shared by all applications and test types
 */


use app\application\providers\RateChain;
use app\application\providers\RateChainProviderInterface;
use app\application\services\LogServiceInterface;
use app\domain\repositories\CurrencyRepositoryInterface;
use app\infrastructure\models\Currency;
use app\infrastructure\repositories\CurrencyRepository;
use tests\components\DummyQueue;
use tests\components\ExchangeRateProvider;
use tests\components\YiiMailer;
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
            RateChainProviderInterface::class => function (Container $container) {
                $exchangeRateProvider = new ExchangeRateProvider(
                    new GuzzleHttp\Client(['base_uri' => getenv('EXCHANGE_RATE_API_URL')]),
                    (string)getenv("EXCHANGE_RATE_API_KEY"),
                    $container->get(LogServiceInterface::class),
                );

                return new RateChain($exchangeRateProvider, (int)getenv("RATE_PROVIDERS_RETRIES"));
            },
            CurrencyRepositoryInterface::class => function (Container $container) {
                return new  CurrencyRepository(Currency::find());
            },
        ]
    ],
    'params' => [],
];

