<?php

namespace app\application\interfaces;

use app\application\exceptions\InvalidCallException;

interface ActivityServiceInterface
{
    /**
     * @param string $email
     * @return array<string,mixed>
     * @throws InvalidCallException
     */
    public function subscribe(string $email): array;
}
