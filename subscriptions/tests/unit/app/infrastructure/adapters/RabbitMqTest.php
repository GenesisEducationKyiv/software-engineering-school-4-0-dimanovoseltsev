<?php

namespace tests\unit\app\infrastructure\adapters;

use app\infrastructure\adapters\RabbitMq;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\queue\amqp_interop\Queue;

class RabbitMqTest extends UnitTestCase
{
    private RabbitMq $adapter;
    private readonly Queue|MockObject $queue;

    public function setUp(): void
    {
        parent::setUp();
        $this->queue = $this->getQueueMock();
        $this->adapter = new RabbitMq($this->queue);
    }

    /**
     * @return Queue|MockObject
     */
    protected function getQueueMock(): Queue|MockObject
    {
        return $this->getMockBuilder(Queue::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'push',
            ])
            ->getMock();
    }

    public function testSendMessage()
    {
        $body = ['id' => 1];
        $this->queue->expects($this->once())
            ->method('push')
            ->with($body)
            ->willReturn('a1');
        $actual = $this->adapter->sendMessage($body);
    }
}
