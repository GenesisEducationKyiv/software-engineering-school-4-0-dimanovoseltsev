<?php

namespace app\shared\domain\interfaces;

interface Arrayable
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
