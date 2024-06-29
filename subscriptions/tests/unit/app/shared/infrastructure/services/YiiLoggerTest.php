<?php

namespace tests\unit\app\shared\infrastructure\services;

use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\log\Logger;

class YiiLoggerTest extends UnitTestCase
{
    private YiiLogger $service;
    private Logger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->getLoggerMock();
        $this->service = new YiiLogger($this->logger);
    }

    /**
     * @return Logger|MockObject
     */
    protected function getLoggerMock(): Logger|MockObject
    {
        return $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['log']
            )
            ->getMock();
    }

    public function testLog()
    {
        $message = 'message';
        $category = 'app';

        $this->logger->expects($this->once())
            ->method('log')
            ->with($message, Logger::LEVEL_INFO, $category);

        $this->service->log($message, $category);
    }

}

