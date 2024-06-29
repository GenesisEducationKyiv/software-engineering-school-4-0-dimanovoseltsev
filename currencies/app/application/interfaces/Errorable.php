<?php

namespace app\application\interfaces;

interface Errorable
{
    /**
     * @return array<string, mixed>
     */
    public function getErrors(): array;
}
