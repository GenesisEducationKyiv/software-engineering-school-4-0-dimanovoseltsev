<?php

use app\subscriptions\application\actions\SendEmail;
use app\subscriptions\application\actions\SendEmailInterface;
use app\subscriptions\application\actions\SendEmailsScheduled;
use app\subscriptions\application\actions\SendEmailsScheduledInterface;
use app\subscriptions\application\actions\Subscribe;
use app\subscriptions\application\actions\SubscribeInterface;
use app\subscriptions\application\services\MailService;
use app\subscriptions\application\services\MailServiceInterface;
use app\subscriptions\application\services\PublisherService;
use app\subscriptions\application\services\PublisherServiceInterface;
use app\subscriptions\application\services\SubscriptionService;
use app\subscriptions\application\services\SubscriptionServiceInterface;
use app\subscriptions\domain\repositories\SubscriptionRepositoryInterface;
use app\subscriptions\infrastructure\adapters\Mailer;
use app\subscriptions\infrastructure\adapters\RabbitMq;
use app\subscriptions\infrastructure\models\Subscription;
use app\subscriptions\infrastructure\repositories\SubscriptionRepository;
use yii\di\Container;


return [
    // repositories
    SubscriptionRepositoryInterface::class => function (Container $container) {
        return new SubscriptionRepository(
            Subscription::find(),
            (int)getenv("BREAK_BETWEEN_SENDING_EMAIL"),
        );
    },

    // services
    SubscriptionServiceInterface::class => function (Container $container) {
        return new SubscriptionService($container->get(SubscriptionRepositoryInterface::class));
    },
    PublisherServiceInterface::class => function (Container $container) {
        return new PublisherService(new RabbitMq(Yii::$app->sendEmailQueue));
    },
    MailServiceInterface::class => function (Container $container) {
        return new MailService(new Mailer(\Yii::$app->mailer));
    },

    // actions
    SubscribeInterface::class => function (Container $container) {
        return new Subscribe($container->get(SubscriptionServiceInterface::class));
    },
    SendEmailsScheduledInterface::class => function (Container $container) {
        return new SendEmailsScheduled(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(PublisherServiceInterface::class),
        );
    },
    SendEmailInterface::class => function (Container $container) {
        return new SendEmail(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(MailServiceInterface::class),
        );
    },
];
