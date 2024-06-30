<?php

use app\application\actions\RetrieveActualCurrencyRate;
use app\application\actions\RetrieveActualCurrencyRateInterface;
use app\application\actions\SendEmailsScheduled;
use app\application\actions\SendEmailsScheduledInterface;
use app\application\actions\Subscribe;
use app\application\actions\SubscribeInterface;
use app\application\adapters\EventBusInterface;
use app\application\services\CurrencyService;
use app\application\services\CurrencyServiceInterface;
use app\application\services\LogServiceInterface;
use app\application\services\SubscriptionService;
use app\application\services\SubscriptionServiceInterface;
use app\domain\repositories\CurrencyRepositoryInterface;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\models\Subscription;
use app\infrastructure\repositories\CurrencyRepository;
use app\infrastructure\repositories\SubscriptionRepository;
use app\infrastructure\services\YiiLogger;
use GuzzleHttp\Client as GuzzleClient;
use yii\di\Container;

return [
    // services
    LogServiceInterface::class => function (Container $container) {
        return new YiiLogger(Yii::$app->log->getLogger());
    },
    // repositories
    SubscriptionRepositoryInterface::class => function (Container $container) {
        return new SubscriptionRepository(
            Subscription::find(),
            (int)getenv("BREAK_BETWEEN_SENDING_EMAIL"),
        );
    },
    CurrencyRepositoryInterface::class => function (Container $container) {
        return new CurrencyRepository(
            new GuzzleClient(['base_uri' => getenv('CURRENCY_BASE_URL')]),
        );
    },
    EventBusInterface::class => function (Container $container) {
        return new EventBusRabbitMQ(Yii::$app->eventBusQueue);
    },

    // services
    SubscriptionServiceInterface::class => function (Container $container) {
        return new SubscriptionService($container->get(SubscriptionRepositoryInterface::class));
    },
    CurrencyServiceInterface::class => function (Container $container) {
        return new CurrencyService(
            $container->get(CurrencyRepositoryInterface::class),
            $container->get(LogServiceInterface::class),
        );
    },

    // actions
    SubscribeInterface::class => function (Container $container) {
        return new Subscribe($container->get(SubscriptionServiceInterface::class));
    },
    SendEmailsScheduledInterface::class => function (Container $container) {
        return new SendEmailsScheduled(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
    RetrieveActualCurrencyRateInterface::class => function (Container $container) {
        return new RetrieveActualCurrencyRate(
            $container->get(CurrencyServiceInterface::class),
        );
    },
];
