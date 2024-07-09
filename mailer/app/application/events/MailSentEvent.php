<?php

namespace app\application\events;

use app\application\interfaces\EventInterface;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

class MailSentEvent implements EventInterface
{
    /**
     * @param Subscription $subscription
     * @param int $timestamp
     */
    public function __construct(
        private readonly Subscription $subscription,
        private readonly int $timestamp,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'mail.sent';
    }

    /**
     * @return array<string,mixed>
     */
    public function getBody(): array
    {
        return [
            'email' => $this->subscription->getEmail()->value(),
            'timestamp' => $this->timestamp,
        ];
    }
}
