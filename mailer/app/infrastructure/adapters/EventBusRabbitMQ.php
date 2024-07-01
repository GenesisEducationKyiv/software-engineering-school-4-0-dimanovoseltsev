<?php

namespace app\infrastructure\adapters;

use app\application\adapters\EventBusInterface;
use app\application\interfaces\EventInterface;
use Enqueue\AmqpLib\AmqpContext;
use PhpAmqpLib\Channel\AMQPChannel;
use yii\queue\amqp_interop\Queue;

class EventBusRabbitMQ implements EventBusInterface
{
    /**
     * @param Queue $queue
     * @param string $exchangeName
     * @param string $exchangeType
     */
    public function __construct(
        private readonly Queue $queue,
        private readonly string $exchangeName,
        private readonly string $exchangeType,
    ) {
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function publish(EventInterface $event): void
    {
        $this->queue->routingKey = $event->getName();
        $this->queue->push($event->getBody());
    }

    /**
     * @return AMQPChannel
     */
    private function getChanel(): AMQPChannel
    {
        /** @var AmqpContext $context */
        $context = $this->queue->getContext();
        return $context->getLibChannel();
    }

    /**
     * @param string $queue
     * @param string $routing
     * @return void
     */
    public function subscribe(string $queue, string $routing): void
    {
        $chanel = $this->getChanel();

        $chanel->exchange_declare($this->exchangeName, $this->exchangeType, false, true, false);
        $chanel->queue_declare($queue, false, true, false, false, false, [], null);
        $chanel->queue_bind($queue, $this->exchangeName, $routing);
    }

    /**
     * @param string $queue
     * @param string $routing
     * @return void
     */
    public function unsubscribe(string $queue, string $routing): void
    {
        $chanel = $this->getChanel();
        $chanel->queue_unbind($queue, $this->exchangeName, $routing);
        $chanel->queue_delete($queue);
    }
}
