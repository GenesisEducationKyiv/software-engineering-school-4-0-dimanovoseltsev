<?php

namespace app\dto\subscription;

readonly class CreateDto
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
