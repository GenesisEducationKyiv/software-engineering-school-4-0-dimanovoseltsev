<?php

namespace app\application\events;

use app\application\interfaces\EventInterface;
use app\application\traits\TimestampTrait;
use app\domain\entities\Currency;
use Ramsey\Uuid\Uuid;

class CurrencyUpdateEvent implements EventInterface
{
    use TimestampTrait;

    /**
     * @param Currency $currency
     */
    public function __construct(
        private readonly Currency $currency,
    ) {
        $this->setCurrentTimestamp();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'currency.update';
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
            'data' => $this->currency->toArray(),
        ];
    }
}
