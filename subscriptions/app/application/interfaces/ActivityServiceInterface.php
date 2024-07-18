<?php

namespace app\application\interfaces;

use app\application\exceptions\InvalidCallException;

interface ActivityServiceInterface
{
    /**
     * @param string $email
     * @return array{
     *     customer: array{
     *       id: int,
     *       email: string
     *     },
     *     subscription: array{
     *       id: int,
     *       email: string,
     *       createdAt: int,
     *       updatedAt: int,
     *       lastSendAt: int
     *     }
     *  }
     * /
     * @throws InvalidCallException
     */
    public function subscribe(string $email): array;
}
