<?php

use app\application\actions\CreateOrUpdateCurrency;
use app\application\actions\CreateOrUpdateCurrencyInterface;
use app\application\actions\ImportRates;
use app\application\actions\ImportRatesInterface;
use app\application\actions\RetrieveCurrencyByCode;
use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\enums\CurrencyIso;
use app\application\providers\RateChain;
use app\application\providers\RateChainProviderInterface;
use app\application\services\CurrencyService;
use app\application\services\CurrencyServiceInterface;
use app\application\services\LogServiceInterface;
use app\application\services\RateService;
use app\application\services\RateServiceInterface;
use app\domain\repositories\CurrencyRepositoryInterface;
use app\infrastructure\models\Currency;
use app\infrastructure\providers\CoinbaseProvider;
use app\infrastructure\providers\ExchangeRateProvider;
use app\infrastructure\repositories\CurrencyCacheRepository;
use app\infrastructure\repositories\CurrencyRepository;
use GuzzleHttp\Client as GuzzleClient;
use yii\di\Container;


return [
    // repositories
    CurrencyRepositoryInterface::class => function (Container $container) {
        return new  CurrencyCacheRepository(
            new CurrencyRepository(Currency::find()),
            Yii::$app->cache,
            (int)getenv("RATE_CACHE_TTL")
        );
    },

    // services
    CurrencyServiceInterface::class => function (Container $container) {
        return new CurrencyService($container->get(CurrencyRepositoryInterface::class));
    },
    RateServiceInterface::class => function (Container $container) {
        return new RateService($container->get(RateChainProviderInterface::class));
    },
    RateChainProviderInterface::class => function (Container $container) {
        $retries = (int)(getenv("RATE_CACHE_TTL") ?: 1);
        $exchangeRateProvider = new ExchangeRateProvider(
            new GuzzleClient(['base_uri' => getenv('EXCHANGE_RATE_API_URL')]),
            (string)getenv("EXCHANGE_RATE_API_KEY"),
            $container->get(LogServiceInterface::class),
        );

        $coinbaseProvider = new CoinbaseProvider(
            new GuzzleClient(['base_uri' => getenv('COINBASE_API_URL')]),
            $container->get(LogServiceInterface::class),
        );

        $chain = new RateChain($exchangeRateProvider, $retries);
        $chain->setNext(new RateChain($coinbaseProvider, $retries));
        return $chain;
    },

    // actions
    RetrieveCurrencyByCodeInterface::class => function (Container $container) {
        return new RetrieveCurrencyByCode($container->get(CurrencyServiceInterface::class));
    },
    CreateOrUpdateCurrencyInterface::class => function (Container $container) {
        return new CreateOrUpdateCurrency($container->get(CurrencyServiceInterface::class));
    },
    ImportRatesInterface::class => function (Container $container) {
        return new ImportRates(
            $container->get(RateServiceInterface::class),
            $container->get(CreateOrUpdateCurrencyInterface::class),
            CurrencyIso::from((string)getenv("BASE_CURRENCY")),
            CurrencyIso::from((string)getenv("IMPORTED_CURRENCY")),
        );
    },
    LogServiceInterface::class => function (Container $container) {
        return new \app\infrastructure\services\YiiLogger(\Yii::$app->log->getLogger());
    },
];
