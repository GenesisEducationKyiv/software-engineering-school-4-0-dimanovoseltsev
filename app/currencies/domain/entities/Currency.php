<?php

namespace app\currencies\domain\entities;

use app\currencies\domain\valueObjects\Iso3;
use app\currencies\domain\valueObjects\Rate;
use app\shared\application\interfaces\Arrayable;
use app\shared\domain\valueObjects\Id;
use app\shared\domain\valueObjects\Timestamp;

class Currency implements Arrayable
{
    /**
     * @param Id $id
     * @param Iso3 $iso3
     * @param Rate $rate
     * @param Timestamp $createdAt
     * @param Timestamp $updatedAt
     */
    public function __construct(
        private Id $id,
        private Iso3 $iso3,
        private Rate $rate,
        private Timestamp $createdAt,
        private Timestamp $updatedAt,
    ) {
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @param Id $id
     * @return void
     */
    public function setId(Id $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Iso3
     */
    public function getIso3(): Iso3
    {
        return $this->iso3;
    }

    /**
     * @param Iso3 $iso3
     * @return void
     */
    public function setIso3(Iso3 $iso3): void
    {
        $this->iso3 = $iso3;
    }

    /**
     * @return Rate
     */
    public function getRate(): Rate
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     * @return void
     */
    public function setRate(Rate $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return Timestamp
     */
    public function getCreatedAt(): Timestamp
    {
        return $this->createdAt;
    }

    /**
     * @param Timestamp $createdAt
     * @return void
     */
    public function setCreatedAt(Timestamp $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Timestamp
     */
    public function getUpdatedAt(): Timestamp
    {
        return $this->updatedAt;
    }

    /**
     * @param Timestamp $updatedAt
     * @return void
     */
    public function setUpdatedAt(Timestamp $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'iso3' => $this->iso3->value(),
            'rate' => $this->rate->value(),
            'createdAt' => $this->createdAt->value(),
            'updatedAt' => $this->updatedAt->value(),
        ];
    }
}
