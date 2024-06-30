<?php

namespace app\application\interfaces;

interface EventInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getBody(): array;
}
