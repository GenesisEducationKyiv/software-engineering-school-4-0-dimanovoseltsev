<?php

namespace app\domain\entities;

use app\domain\interfaces\Arrayable;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class Currency implements Arrayable
{
    /**
     * @param Iso3 $iso3
     * @param Rate $rate
     * @param Timestamp $updatedAt
     */
    public function __construct(
        private Iso3 $iso3,
        private Rate $rate,
        private Timestamp $updatedAt,
    ) {
    }

    /**
     * @return Iso3
     */
    public function getIso3(): Iso3
    {
        return $this->iso3;
    }

    /**
     * @return Rate
     */
    public function getRate(): Rate
    {
        return $this->rate;
    }

    /**
     * @return Timestamp
     */
    public function getUpdatedAt(): Timestamp
    {
        return $this->updatedAt;
    }


    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'iso3' => $this->getIso3()->value(),
            'rate' => $this->getRate()->value(),
            'updatedAt' => $this->getUpdatedAt()->value(),
        ];
    }
}
