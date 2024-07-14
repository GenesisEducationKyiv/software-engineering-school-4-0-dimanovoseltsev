<?php

namespace app\application\interfaces;

interface ActivityServiceInterface
{
    /**
     * @param string $email
     * @return array<string, mixed>
     */
    public function subscribe(string $email): array;
}
