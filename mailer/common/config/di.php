<?php

use app\application\actions\SendEmail;
use app\application\actions\SendEmailInterface;
use app\application\adapters\EventBusInterface;
use app\application\services\LogServiceInterface;
use app\application\services\MailService;
use app\application\services\MailServiceInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\adapters\Mailer;
use app\infrastructure\services\YiiLogger;
use PhpAmqpLib\Exchange\AMQPExchangeType;
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

    // actions
    SendEmailInterface::class => function (Container $container) {
        return new SendEmail(
            $container->get(MailServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
];
