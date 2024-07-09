<?php

namespace app\application\services;

interface LogServiceInterface
{
    /**
     * @param string $message
     * @return void
     */
    public function log(string $message): void;
}
