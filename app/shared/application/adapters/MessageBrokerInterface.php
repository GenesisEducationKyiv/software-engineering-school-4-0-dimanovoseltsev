<?php

namespace app\shared\application\adapters;

interface MessageBrokerInterface
{
    /**
     * @param array<mixed> $body
     * @return string|null
     */
    public function sendMessage(array $body): ?string;
}
