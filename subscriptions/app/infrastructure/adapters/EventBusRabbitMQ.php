<?php

namespace app\infrastructure\adapters;

use app\application\adapters\EventBusInterface;
use app\application\interfaces\EventInterface;
use yii\queue\amqp_interop\Queue;

class EventBusRabbitMQ implements EventBusInterface
{
    /**
     * @param Queue $queue
     */
    public function __construct(
        private readonly Queue $queue,
    ) {
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function publish(EventInterface $event): void
    {
        $this->queue->push([
            'event' => $event->getName(),
            'body' => $event->getBody(),
        ]);
    }
}
