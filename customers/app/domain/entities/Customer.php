<?php

namespace app\domain\entities;

use app\domain\interfaces\Arrayable;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;

class Customer implements Arrayable
{
    /**
     * @param Id $id
     * @param Email $email
     */
    public function __construct(
        private Id $id,
        private Email $email,
    ) {
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'email' => $this->email->value(),
        ];
    }
}
