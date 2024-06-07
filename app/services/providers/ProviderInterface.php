<?php

namespace app\services\providers;

interface ProviderInterface
{
    /**
     * @return array<string, float>
     */
    public function getActualRates(): array;
}
