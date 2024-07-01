<?php

namespace app\domain\entities;

use app\domain\interfaces\Arrayable;
use app\domain\valueObjects\Email;

class Subscription implements Arrayable
{
    /**
     * @param Email $email
     */
    public function __construct(
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
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email->value(),
        ];
    }
}
