<?php


use app\models\Currency;
use app\models\Subscription;
use app\repositories\CurrencyCacheRepository;
use app\repositories\CurrencyRepository;
use app\repositories\CurrencyRepositoryInterface;
use app\repositories\SubscriptionRepository;
use app\repositories\SubscriptionRepositoryInterface;
use app\services\CurrenciesService;
use app\services\CurrenciesServiceInterface;
use app\services\MailService;
use app\services\MailServiceInterface;
use app\services\SubscriptionService;
use app\services\SubscriptionServiceInterface;
use yii\di\Container;
use yii\symfonymailer\Mailer;

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
            'class' => Mailer::class,
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
        'definitions' => [
            // repositories
            CurrencyRepositoryInterface::class => function (Container $container) {
                return new CurrencyCacheRepository(
                    new CurrencyRepository(Currency::find()),
                    Yii::$app->cache,
                    (int)getenv("RATE_CACHE_TTL")
                );
            },
            SubscriptionRepositoryInterface::class => function (Container $container) {
                return new SubscriptionRepository(Subscription::find());
            },

            // services
            CurrenciesServiceInterface::class => function (Container $container) {
                return new CurrenciesService(
                    $container->get(CurrencyRepositoryInterface::class)
                );
            },
            SubscriptionServiceInterface::class => function (Container $container) {
                return new SubscriptionService(
                    $container->get(SubscriptionRepositoryInterface::class)
                );
            },
            MailServiceInterface::class => function (Container $container) {
                return new MailService(Yii::$app->mailer);
            },
        ]
    ],
];
