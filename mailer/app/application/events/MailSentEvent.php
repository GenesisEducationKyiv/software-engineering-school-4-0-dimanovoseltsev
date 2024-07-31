<?php

namespace app\application\events;

use app\application\interfaces\EventInterface;
use app\application\traits\TimestampTrait;
use app\domain\entities\Subscription;
use Ramsey\Uuid\Uuid;

class MailSentEvent implements EventInterface
{
    use TimestampTrait;

    /**
     * @param Subscription $subscription
     */
    public function __construct(
        private readonly Subscription $subscription,
    ) {
        $this->setCurrentTimestamp();
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
            'eventId' => Uuid::uuid7()->toString(),
            'eventType' => $this->getName(),
            'timestamp' => $this->getTimestamp(),
            'data' => [
                'email' => $this->subscription->getEmail()->value(),
                'timestamp' => $this->getTimestamp(),
            ],
        ];
    }
}
