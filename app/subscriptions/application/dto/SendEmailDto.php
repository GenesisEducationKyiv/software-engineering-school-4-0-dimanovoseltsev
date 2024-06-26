<?php

namespace app\subscriptions\application\dto;

readonly class SendEmailDto
{
    /**
     * @param string $email
     * @param int $timestamp
     */
    public function __construct(
        private string $email,
        private int $timestamp,
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
