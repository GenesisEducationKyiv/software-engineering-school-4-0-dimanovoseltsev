<?php

namespace app\application\events;

use app\application\interfaces\EventInterface;
use app\application\traits\TimestampTrait;
use app\domain\entities\Customer;
use Ramsey\Uuid\Uuid;

class CustomerCreateEvent implements EventInterface
{
    use TimestampTrait;

    /**
     * @param Customer $customer
     */
    public function __construct(
        private readonly Customer $customer
    ) {
        $this->setCurrentTimestamp();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'customer.create';
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
            'data' => $this->customer->toArray(),
        ];
    }
}
