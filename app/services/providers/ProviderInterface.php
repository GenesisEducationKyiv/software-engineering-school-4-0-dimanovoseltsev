<?php

namespace app\services\providers;

interface ProviderInterface
{
    /**
     * @return array
     */
    public function getActualRates(): array;
}
