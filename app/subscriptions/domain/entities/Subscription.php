<?php

namespace app\subscriptions\domain\entities;

use app\shared\application\interfaces\Arrayable;
use app\shared\domain\valueObjects\Id;
use app\shared\domain\valueObjects\Timestamp;
use app\subscriptions\domain\valueObjects\Email;

class Subscription implements Arrayable
{
    /**
     * @param Id $id
     * @param Email $email
     * @param Timestamp $createdAt
     * @param Timestamp $updatedAt
     * @param Timestamp $lastSendAt
     */
    public function __construct(
        private Id $id,
        private Email $email,
        private Timestamp $createdAt,
        private Timestamp $updatedAt,
        private Timestamp $lastSendAt,

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
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return void
     */
    public function setEmail(Email $email): void
    {
        $this->email = $email;
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
     * @return Timestamp
     */
    public function getLastSendAt(): Timestamp
    {
        return $this->lastSendAt;
    }

    /**
     * @param Timestamp $lastSendAt
     * @return void
     */
    public function setLastSendAt(Timestamp $lastSendAt): void
    {
        $this->lastSendAt = $lastSendAt;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'email' => $this->email->value(),
            'createdAt' => $this->createdAt->value(),
            'updatedAt' => $this->updatedAt->value(),
            'lastSendAt' => $this->lastSendAt->value(),
        ];
    }
}
