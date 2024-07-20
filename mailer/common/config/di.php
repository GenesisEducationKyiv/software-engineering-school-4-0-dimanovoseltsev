<?php

use app\application\actions\DeleteSubscription;
use app\application\actions\DeleteSubscriptionInterface;
use app\application\actions\RetrieveCurrencyByCode;
use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\actions\RetrieveSubscriptionByEmail;
use app\application\actions\RetrieveSubscriptionByEmailInterface;
use app\application\actions\SaveCurrency;
use app\application\actions\SaveCurrencyInterface;
use app\application\actions\SaveSubscription;
use app\application\actions\SaveSubscriptionInterface;
use app\application\actions\SendEmail;
use app\application\actions\SendEmailInterface;
use app\application\actions\SendEmailsScheduled;
use app\application\actions\SendEmailsScheduledInterface;
use app\application\adapters\EventBusInterface;
use app\application\services\CurrencyService;
use app\application\services\CurrencyServiceInterface;
use app\application\services\LogServiceInterface;
use app\application\services\MailService;
use app\application\services\MailServiceInterface;
use app\application\services\MetricServiceInterface;
use app\application\services\PublisherService;
use app\application\services\PublisherServiceInterface;
use app\application\services\SubscriptionService;
use app\application\services\SubscriptionServiceInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\adapters\Mailer;
use app\infrastructure\adapters\RabbitMq;
use app\infrastructure\models\Currency;
use app\infrastructure\models\Subscription;
use app\infrastructure\repositories\CurrencyRepository;
use app\infrastructure\repositories\SubscriptionRepository;
use app\infrastructure\services\PrometheusMetricService;
use app\infrastructure\services\YiiLogger;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use yii\di\Container;

return [
    // services
    LogServiceInterface::class => function (Container $container) {
        return new YiiLogger(\Yii::$app->log->getLogger());
    },
    MailServiceInterface::class => function (Container $container) {
        return new MailService(new Mailer(\Yii::$app->mailer));
    },
    EventBusInterface::class => function (Container $container) {
        return new EventBusRabbitMQ(
            Yii::$app->eventBusQueue,
            (string)getenv("RABBITMQ_EVENT_BUS_EXCHANGE"),
            AMQPExchangeType::TOPIC
        );
    },
    CurrencyServiceInterface::class => function (Container $container) {
        return new CurrencyService(
            new CurrencyRepository(Currency::find())
        );
    },
    SubscriptionServiceInterface::class => function (Container $container) {
        return new SubscriptionService(
            new SubscriptionRepository(Subscription::find())
        );
    },
    PublisherServiceInterface::class => function (Container $container) {
        return new PublisherService(new RabbitMq(Yii::$app->sendEmailQueue));
    },

    MetricServiceInterface::class => function (Container $container) {
        return new PrometheusMetricService(
            new CollectorRegistry(new \Prometheus\Storage\Redis([
                'host' => (string)getenv("REDIS_HOST"),
                'port' =>  (string)getenv("REDIS_PORT"),
            ])),
            new RenderTextFormat(),
            'app'
        );
    },

    // actions
    SendEmailInterface::class => function (Container $container) {
        return new SendEmail(
            $container->get(MailServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
    SaveCurrencyInterface::class => function (Container $container) {
        return new SaveCurrency(
            $container->get(CurrencyServiceInterface::class),
            $container->get(LogServiceInterface::class),
        );
    },
    SaveSubscriptionInterface::class => function (Container $container) {
        return new SaveSubscription(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(LogServiceInterface::class),
        );
    },
    DeleteSubscriptionInterface::class => function (Container $container) {
        return new DeleteSubscription(
            $container->get(SubscriptionServiceInterface::class),
        );
    },
    SendEmailsScheduledInterface::class => function (Container $container) {
        return new SendEmailsScheduled(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(PublisherServiceInterface::class),
        );
    },
    RetrieveSubscriptionByEmailInterface::class => function (Container $container) {
        return new RetrieveSubscriptionByEmail(
            $container->get(SubscriptionServiceInterface::class),
        );
    },
    RetrieveCurrencyByCodeInterface::class => function (Container $container) {
        return new RetrieveCurrencyByCode(
            $container->get(CurrencyServiceInterface::class),
        );
    },
];
