<?php

namespace app\subscriptions\infrastructure\adapters;

use app\application\adapters\MessageBrokerInterface;
use yii\queue\amqp_interop\Queue;

class RabbitMq implements MessageBrokerInterface
{
    /**
     * @param Queue $queue
     */
    public function __construct(
        private readonly Queue $queue,
    ) {
    }

    /**
     * @param array<mixed> $body
     * @return string|null
     */
    public function sendMessage(array $body): ?string
    {
        return $this->queue->push($body);
    }
}
