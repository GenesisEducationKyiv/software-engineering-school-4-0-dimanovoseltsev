<?php

namespace app\application\interfaces;

interface ActivityServiceInterface
{
    /**
     * @param string $email
     * @return array {
     *   customer: {
     *     id?: integer,
     *     email?: string
     *   },
     *   subscription: {
     *     id?: integer,
     *     email?: integer,
     *     createdAt?: integer,
     *     updatedAt?: string,
     *     lastSendAt?: integer
     *   }
     * }
     */
    public function subscribe(string $email): array;
}
