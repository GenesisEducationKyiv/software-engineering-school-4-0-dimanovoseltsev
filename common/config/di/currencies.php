<?php

use app\currencies\application\actions\CreateOrUpdateCurrency;
use app\currencies\application\actions\CreateOrUpdateCurrencyInterface;
use app\currencies\application\actions\ImportRates;
use app\currencies\application\actions\ImportRatesInterface;
use app\currencies\application\actions\RetrieveCurrencyByCode;
use app\currencies\application\actions\RetrieveCurrencyByCodeInterface;
use app\currencies\application\providers\RateChain;
use app\currencies\application\providers\RateChainProviderInterface;
use app\currencies\application\services\CurrencyService;
use app\currencies\application\services\CurrencyServiceInterface;
use app\currencies\application\services\RateService;
use app\currencies\application\services\RateServiceInterface;
use app\currencies\domain\repositories\CurrencyRepositoryInterface;
use app\currencies\infrastructure\models\Currency;
use app\currencies\infrastructure\providers\CoinbaseProvider;
use app\currencies\infrastructure\providers\ExchangeRateProvider;
use app\currencies\infrastructure\repositories\CurrencyCacheRepository;
use app\currencies\infrastructure\repositories\CurrencyRepository;
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
        $exchangeRateProvider = new ExchangeRateProvider(
            new GuzzleHttp\Client(['base_uri' => getenv('EXCHANGE_RATE_API_URL')]),
            (string)getenv("EXCHANGE_RATE_API_KEY"),
        );

        $coinbaseProvider = new CoinbaseProvider(
            new GuzzleHttp\Client(['base_uri' => getenv('COINBASE_API_URL')]),
        );

        $chain = new RateChain($exchangeRateProvider);
        $chain->setNext(new RateChain($coinbaseProvider));
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
            (string)getenv("BASE_CURRENCY"),
            (string)getenv("IMPORTED_CURRENCY"),
        );
    },
];
