<?php

namespace tests\unit\app\infrastructure\adapters;

use app\application\events\CreateMailEvent;
use app\infrastructure\adapters\EventBusRabbitMQ;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\queue\amqp_interop\Queue;

class EventBusRabbitMQTest extends UnitTestCase
{
    private EventBusRabbitMQ $adapter;
    private Queue|MockObject $queue;

    public function setUp(): void
    {
        parent::setUp();
        $this->queue = $this->getQueueMock();
        $this->adapter = new EventBusRabbitMQ($this->queue, 'exchange', AMQPExchangeType::TOPIC);
    }

    /**
     * @return Queue|MockObject
     */
    protected function getQueueMock(): Queue|MockObject
    {
        return $this->getMockBuilder(Queue::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['push']
            )
            ->getMock();
    }

    public function testPublish()
    {
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity();
        $event = new CreateMailEvent($currency, $subscription);

        $this->queue->expects($this->once())
            ->method('push')
            ->with($event->getBody());

        $this->adapter->publish($event);
    }
}

