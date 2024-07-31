<?php

namespace app\infrastructure\services;

use app\application\services\LogServiceInterface;
use yii\log\Logger;

class YiiLogger implements LogServiceInterface
{
    /**
     * @param Logger $logger
     * @param string $category
     */
    public function __construct(
        private readonly Logger $logger,
        private readonly string $category = 'app'
    ) {
    }

    private function log(string $message, int $level): void
    {
        $this->logger->log($message, $level, $this->category);
    }

    public function error(string $message): void
    {
        $this->log($message, Logger::LEVEL_ERROR);
    }

    public function info(string $message): void
    {
        $this->log($message, Logger::LEVEL_INFO);
    }

    public function warn(string $message): void
    {
        $this->log($message, Logger::LEVEL_WARNING);
    }

    public function debug(string $message): void
    {
        $this->log($message, Logger::LEVEL_TRACE);
    }
}
