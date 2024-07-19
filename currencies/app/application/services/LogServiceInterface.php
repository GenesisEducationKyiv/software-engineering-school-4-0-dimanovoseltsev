<?php

namespace app\application\services;

interface LogServiceInterface
{
    public function error(string $message): void;

    public function info(string $message): void;

    public function warn(string $message): void;

    public function debug(string $message): void;
}
