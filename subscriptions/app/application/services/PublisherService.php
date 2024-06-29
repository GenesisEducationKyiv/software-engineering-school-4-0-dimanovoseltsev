<?php

namespace app\application\services;

use app\application\adapters\MessageBrokerInterface;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

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
     * @param Subscription $subscription
     * @param Currency $currency
     * @return void
     */
    public function enqueueMessageForSending(Subscription $subscription, Currency $currency): void
    {
        $this->sendMessageQueue->sendMessage([
            'email' => $subscription->getEmail()->value(),
            'currency' => $currency->toArray(),
        ]);
    }
}
