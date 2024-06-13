<?php

namespace app\shared\application\interfaces;

interface Errorable
{
    /**
     * @return array
     */
    public function getErrors(): array;
}
