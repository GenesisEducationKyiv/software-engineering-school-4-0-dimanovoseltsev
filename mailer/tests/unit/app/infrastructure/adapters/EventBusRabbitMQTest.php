<?php

namespace tests\unit\app\infrastructure\adapters;

use app\application\events\MailCreateEvent;
use app\application\events\MailSentEvent;
use app\infrastructure\adapters\EventBusRabbitMQ;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\queue\amqp_interop\Queue;

class EventBusRabbitMQTest extends UnitTestCase
{
    private EventBusRabbitMQ $adapter;
    private Queue|MockObject $queue;
    private string $exchangeName = 'exchange';
    private string $exchangeType = AMQPExchangeType::TOPIC;

    public function setUp(): void
    {
        parent::setUp();
        $this->queue = $this->getQueueMock();
        $this->adapter = new EventBusRabbitMQ($this->queue, $this->exchangeName, $this->exchangeType);
    }

    /**
     * @return Queue|MockObject
     */
    protected function getQueueMock(): Queue|MockObject
    {
        return $this->getMockBuilder(Queue::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['push', 'getContext']
            )
            ->getMock();
    }

    public function testPublish()
    {
        $subscription = $this->getSubscriptionEntity();
        $event = new MailSentEvent($subscription, time());

        $this->queue->expects($this->once())
            ->method('push')
            ->with($event->getBody());

        $this->adapter->publish($event);
    }

    /**
     * @return AMQPChannel|MockObject
     */
    private function getAMQPChannelMock(): AMQPChannel|MockObject
    {
        $context = $this->getMockBuilder(\Enqueue\AmqpLib\AmqpContext::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['getLibChannel']
            )
            ->getMock();

        $chanel = $this->getMockBuilder(AMQPChannel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['exchange_declare', 'queue_declare', 'queue_bind', 'queue_unbind', 'queue_delete']
            )
            ->getMock();

        $this->queue->expects($this->any())
            ->method('getContext')
            ->willReturn($context);

        $context->expects($this->any())
            ->method('getLibChannel')
            ->willReturn($chanel);

        return $chanel;
    }

    public function testSubscribe()
    {
        $queue = 'q1';
        $routing = '1';

        $chanel = $this->getAMQPChannelMock();

        $chanel->expects(self::once())
            ->method('exchange_declare')
            ->with($this->exchangeName, $this->exchangeType, false, true, false);

        $chanel->expects(self::once())
            ->method('queue_declare')
            ->with($queue, false, true, false, false, false, [], null);

        $chanel->expects(self::once())
            ->method('queue_bind')
            ->with($queue, $this->exchangeName, $routing);

        $this->adapter->subscribe($queue, $routing);
    }

    public function testUnsubscribe()
    {
        $queue = 'q1';
        $routing = '1';

        $chanel = $this->getAMQPChannelMock();

        $chanel->expects(self::once())
            ->method('queue_unbind')
            ->with($queue, $this->exchangeName, $routing);

        $chanel->expects(self::once())
            ->method('queue_delete')
            ->with($queue);

        $this->adapter->unsubscribe($queue, $routing);
    }
}

