<?php

namespace app\shared\application\interfaces;

interface Errorable
{
    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array;
}
