<?php


use app\currencies\application\actions\CreateOrUpdateCurrency;
use app\currencies\application\actions\CreateOrUpdateCurrencyInterface;
use app\currencies\application\actions\ImportRates;
use app\currencies\application\actions\ImportRatesInterface;
use app\currencies\application\actions\RetrieveCurrencyByCode;
use app\currencies\application\actions\RetrieveCurrencyByCodeInterface;
use app\currencies\application\services\CurrencyService;
use app\currencies\application\services\CurrencyServiceInterface;
use app\currencies\application\services\ImportCurrencyService;
use app\currencies\application\services\ImportCurrencyServiceInterface;
use app\currencies\infrastructure\models\Currency;
use app\currencies\infrastructure\repositories\CurrencyCacheRepository;
use app\currencies\infrastructure\repositories\CurrencyRepository;
use app\subscriptions\application\actions\Subscribe;
use app\subscriptions\application\actions\SubscribeInterface;
use app\subscriptions\application\services\SubscriptionService;
use app\subscriptions\application\services\SubscriptionServiceInterface;
use app\subscriptions\infrastructure\models\Subscription;
use app\subscriptions\infrastructure\repositories\SubscriptionRepository;
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
            // services
            SubscriptionServiceInterface::class => function (Container $container) {
                return new SubscriptionService(
                    new SubscriptionRepository(Subscription::find())
                );
            },
            CurrencyServiceInterface::class => function (Container $container) {
                return new CurrencyService(
                    new CurrencyCacheRepository(
                        new CurrencyRepository(Currency::find()),
                        Yii::$app->cache,
                        (int)getenv("RATE_CACHE_TTL")
                    )
                );
            },


            ImportCurrencyServiceInterface::class => function (Container $container) {
                return new ImportCurrencyService(
                     new \app\currencies\infrastructure\providers\EuropeanCentralBankProvider(
                         new GuzzleHttp\Client(['base_uri' => getenv('EXCHANGE_RATE_API_URL')]),
                         (string)getenv("EXCHANGE_RATE_API_LEY"),
                         (string)getenv("BASE_CURRENCY"),
                         (string)getenv("IMPORTED_CURRENCY"),
                     ),
                    $container->get(CreateOrUpdateCurrencyInterface::class)
                );
            },


            // actions currency
            RetrieveCurrencyByCodeInterface::class => function (Container $container) {
                return new RetrieveCurrencyByCode($container->get(CurrencyServiceInterface::class));
            },
            CreateOrUpdateCurrencyInterface::class => function (Container $container) {
                return new CreateOrUpdateCurrency($container->get(CurrencyServiceInterface::class));
            },
            ImportRatesInterface::class => function (Container $container) {
                return new ImportRates($container->get(ImportCurrencyServiceInterface::class));
            },


            // actions sub
            SubscribeInterface::class => function (Container $container) {
                return new Subscribe($container->get(SubscriptionServiceInterface::class));
            },

//
//            // repositories
//            CurrencyRepositoryInterface::class => function (Container $container) {
//                return new CurrencyCacheRepository(
//                    new CurrencyRepository(Currency::find()),
//                    Yii::$app->cache,
//                    (int)getenv("RATE_CACHE_TTL")
//                );
//            },
//            SubscriptionRepositoryInterface::class => function (Container $container) {
//                return new SubscriptionRepository(Subscription::find());
//            },
//
//            // services
//            CurrenciesServiceInterface::class => function (Container $container) {
//                return new CurrenciesService(
//                    $container->get(CurrencyRepositoryInterface::class)
//                );
//            },
//            SubscriptionServiceInterface::class => function (Container $container) {
//                return new SubscriptionService(
//                    $container->get(SubscriptionRepositoryInterface::class)
//                );
//            },
//            MailServiceInterface::class => function (Container $container) {
//                return new MailService(Yii::$app->mailer);
//            },
        ]
    ],
];
