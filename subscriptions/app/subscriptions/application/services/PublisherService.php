<?php

namespace app\subscriptions\application\services;

use app\application\adapters\MessageBrokerInterface;

class PublisherService implements PublisherServiceInterface
{
    /**
     * @param MessageBrokerInterface $sendMessageQueue
     */
    public function __construct(
        private readonly MessageBrokerInterface $sendMessageQueue,
    ) {
    }

    /**
     * @param string $email
     * @param string $currency
     * @return void
     */
    public function enqueueMessageForSending(string $email, string $currency): void
    {
        $this->sendMessageQueue->sendMessage(['email' => $email, 'currency' => $currency]);
    }
}
