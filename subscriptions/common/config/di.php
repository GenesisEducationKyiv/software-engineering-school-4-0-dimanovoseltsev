<?php

use app\application\actions\MailSent;
use app\application\actions\MailSentInterface;
use app\application\actions\Subscribe;
use app\application\actions\SubscribeInterface;
use app\application\actions\Unsubscribe;
use app\application\actions\UnsubscribeInterface;
use app\application\adapters\EventBusInterface;
use app\application\services\LogServiceInterface;
use app\application\services\SubscriptionService;
use app\application\services\SubscriptionServiceInterface;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\models\Subscription;
use app\infrastructure\repositories\SubscriptionRepository;
use app\infrastructure\services\YiiLogger;
use PhpAmqpLib\Exchange\AMQPExchangeType;
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
    EventBusInterface::class => function (Container $container) {
        return new EventBusRabbitMQ(
            Yii::$app->eventBusQueue,
            (string)getenv("RABBITMQ_EVENT_BUS_EXCHANGE"),
            AMQPExchangeType::TOPIC
        );
    },

    // services
    SubscriptionServiceInterface::class => function (Container $container) {
        return new SubscriptionService($container->get(SubscriptionRepositoryInterface::class));
    },

    // actions
    SubscribeInterface::class => function (Container $container) {
        return new Subscribe(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
    MailSentInterface::class => function (Container $container) {
        return new MailSent(
            $container->get(SubscriptionServiceInterface::class),
        );
    },
    UnsubscribeInterface::class => function (Container $container) {
        return new Unsubscribe(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
];
