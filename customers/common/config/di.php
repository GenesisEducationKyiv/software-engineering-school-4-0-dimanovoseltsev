<?php

use app\application\actions\DeleteCustomer;
use app\application\actions\DeleteCustomerInterface;
use app\application\actions\RetrieveCustomerByEmail;
use app\application\actions\RetrieveCustomerByEmailInterface;
use app\application\actions\SaveCustomer;
use app\application\actions\SaveCustomerInterface;
use app\application\adapters\EventBusInterface;
use app\application\services\CustomerService;
use app\application\services\CustomerServiceInterface;
use app\application\services\LogServiceInterface;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\models\Customer;
use app\infrastructure\repositories\CustomerRepository;
use app\infrastructure\services\YiiLogger;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use yii\di\Container;

return [
    // services
    LogServiceInterface::class => function (Container $container) {
        return new YiiLogger(\Yii::$app->log->getLogger());
    },
    EventBusInterface::class => function (Container $container) {
        return new EventBusRabbitMQ(
            Yii::$app->eventBusQueue,
            (string)getenv("RABBITMQ_EVENT_BUS_EXCHANGE"),
            AMQPExchangeType::TOPIC
        );
    },
    CustomerServiceInterface::class => function (Container $container) {
        return new CustomerService(
            new CustomerRepository(Customer::find())
        );
    },

    // actions
    DeleteCustomerInterface::class => function (Container $container) {
        return new DeleteCustomer(
            $container->get(CustomerServiceInterface::class),
            $container->get(LogServiceInterface::class),
        );
    },
    RetrieveCustomerByEmailInterface::class => function (Container $container) {
        return new RetrieveCustomerByEmail(
            $container->get(CustomerServiceInterface::class),
        );
    },
    SaveCustomerInterface::class => function (Container $container) {
        return new SaveCustomer(
            $container->get(CustomerServiceInterface::class),
        );
    },
];
