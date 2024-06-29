<?php

namespace app\application\adapters;

interface MessageBrokerInterface
{
    /**
     * @param array<mixed> $body
     * @return string|null
     */
    public function sendMessage(array $body): ?string;
}
