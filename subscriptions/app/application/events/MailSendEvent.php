<?php

namespace app\application\events;

use app\application\interfaces\EventInterface;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;

class MailSendEvent implements EventInterface
{
    /**
     * @param Currency $currency
     * @param Subscription $subscription
     */
    public function __construct(
        private readonly Currency $currency,
        private readonly Subscription $subscription
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'mail.send';
    }

    public function getBody(): array
    {
        return [
            'email' => $this->subscription->getEmail()->value(),
            'currency' => $this->currency->toArray(),
        ];
    }
}
