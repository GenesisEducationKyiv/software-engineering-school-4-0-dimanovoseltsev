<?php

namespace app\shared\infrastructure\services;

use app\application\services\LogServiceInterface;
use yii\log\Logger;

class YiiLogger implements LogServiceInterface
{
    /**
     * @param Logger $logger
     */
    public function __construct(private readonly Logger $logger)
    {
    }

    /**
     * @param string $message
     * @param string $category
     * @return void
     */
    public function log(string $message, string $category = 'app'): void
    {
        $this->logger->log($message, Logger::LEVEL_INFO, $category);
    }
}
