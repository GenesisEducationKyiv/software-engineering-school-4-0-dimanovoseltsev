<?php

namespace tests\unit\app\infrastructure\services;

use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\log\Logger;

class YiiLoggerTest extends UnitTestCase
{
    private YiiLogger $service;
    private Logger|MockObject $logger;
    private string $category = 'app1';

    public function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->getLoggerMock();
        $this->service = new YiiLogger($this->logger, $this->category);
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

    public function testError()
    {
        $message = 'message';

        $this->logger->expects($this->once())
            ->method('log')
            ->with($message, Logger::LEVEL_ERROR, $this->category);

        $this->service->error($message);
    }

    public function testWarn()
    {
        $message = 'message';

        $this->logger->expects($this->once())
            ->method('log')
            ->with($message, Logger::LEVEL_WARNING, $this->category);

        $this->service->warn($message);
    }

    public function testDebug()
    {
        $message = 'message';

        $this->logger->expects($this->once())
            ->method('log')
            ->with($message, Logger::LEVEL_TRACE, $this->category);

        $this->service->debug($message);
    }

    public function testInfo()
    {
        $message = 'message';

        $this->logger->expects($this->once())
            ->method('log')
            ->with($message, Logger::LEVEL_INFO, $this->category);

        $this->service->info($message);
    }
}
