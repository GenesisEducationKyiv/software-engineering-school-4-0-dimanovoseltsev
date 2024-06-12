<?php


namespace app\subscriptions\application\dto;


readonly class CreateSubscriptionDto
{
    /**
     * @param string $email
     * @param int|null $createdAt
     */
    public function __construct(
        private string $email,
        private ?int $createdAt = null,
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
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}
