<?php

use app\application\actions\CreateCustomer;
use app\application\actions\CreateCustomerInterface;
use app\application\actions\CreateSubscription;
use app\application\actions\CreateSubscriptionInterface;
use app\application\actions\DeleteCustomer;
use app\application\actions\DeleteCustomerInterface;
use app\application\actions\MailSent;
use app\application\actions\MailSentInterface;
use app\application\actions\Subscribe;
use app\application\actions\SubscribeInterface;
use app\application\actions\Unsubscribe;
use app\application\actions\UnsubscribeInterface;
use app\application\adapters\EventBusInterface;
use app\application\interfaces\ActivityServiceInterface;
use app\application\services\CustomerService;
use app\application\services\CustomerServiceInterface;
use app\application\services\LogServiceInterface;
use app\application\services\MetricServiceInterface;
use app\application\services\SubscriptionService;
use app\application\services\SubscriptionServiceInterface;
use app\domain\repositories\CustomerRepositoryInterface;
use app\domain\repositories\SubscriptionRepositoryInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\models\Subscription;
use app\infrastructure\repositories\CustomerRepository;
use app\infrastructure\repositories\SubscriptionRepository;
use app\infrastructure\saga\workflows\SubscriptionWorkflowInterface;
use app\infrastructure\services\PrometheusMetricService;
use app\infrastructure\services\TemporalActivityService;
use app\infrastructure\services\YiiLogger;
use Carbon\CarbonInterval;
use GuzzleHttp\Client;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use yii\di\Container;

return [
    // services
    LogServiceInterface::class => function (Container $container) {
        return new YiiLogger(Yii::$app->log->getLogger());
    },
    // services
    CustomerServiceInterface::class => function (Container $container) {
        return new CustomerService(
            $container->get(CustomerRepositoryInterface::class)
        );
    },
    ActivityServiceInterface::class => function (Container $container) {
        // Create a GRPC service client with the Temporal server address
        $serviceClient = ServiceClient::create((string)getenv("TEMPORAL_ADDRESS"));
        // Start a Workflow
        $client = WorkflowClient::create($serviceClient);
        return new TemporalActivityService(
            $client->newWorkflowStub(
                SubscriptionWorkflowInterface::class,
                WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
                    ->withTaskQueue((string)getenv("TEMPORAL_SUBSCRIPTION_TASK"))
            )
        );
    },

    MetricServiceInterface::class => function (Container $container) {
        return new PrometheusMetricService(
            new CollectorRegistry(new \Prometheus\Storage\Redis([
                'host' => (string)getenv("REDIS_HOST"),
                'port' => (string)getenv("REDIS_PORT"),
            ])),
            new RenderTextFormat(),
            'subscriptions'
        );
    },

    // repositories
    SubscriptionRepositoryInterface::class => function (Container $container) {
        return new SubscriptionRepository(Subscription::find());
    },
    CustomerRepositoryInterface::class => function (Container $container) {
        return new CustomerRepository(
            new Client(['base_uri' => getenv('CUSTOMER_BASE_URL')]),
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
            $container->get(ActivityServiceInterface::class),
            $container->get(LogServiceInterface::class),
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
    CreateCustomerInterface::class => function (Container $container) {
        return new CreateCustomer($container->get(CustomerServiceInterface::class));
    },
    DeleteCustomerInterface::class => function (Container $container) {
        return new DeleteCustomer(
            $container->get(CustomerServiceInterface::class),
            $container->get(LogServiceInterface::class),
        );
    },
    CreateSubscriptionInterface::class => function (Container $container) {
        return new CreateSubscription(
            $container->get(SubscriptionServiceInterface::class),
            $container->get(EventBusInterface::class),
        );
    },
];
