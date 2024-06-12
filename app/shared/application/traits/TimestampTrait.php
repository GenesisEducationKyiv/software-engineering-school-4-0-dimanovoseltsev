<?php

namespace app\shared\application\traits;

trait TimestampTrait
{
    protected int $timestamp = 0;

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return static
     */
    public function setTimestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return static
     */
    public function setCurrentTimestamp(): static
    {
        return $this->setTimestamp(time());
    }
}
