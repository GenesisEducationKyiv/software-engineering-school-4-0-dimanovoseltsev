<?php

namespace app\currencies\application\providers;

interface ProviderInterface
{
    /**
     * @return array<string, float>
     */
    public function getActualRates(): array;
}
