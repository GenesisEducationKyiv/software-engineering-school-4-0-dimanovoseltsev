<?php

namespace app\application\dto;

readonly class DeleteSubscriptionDto
{
    /**
     * @param string $email
     */
    public function __construct(
        private string $email,
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
