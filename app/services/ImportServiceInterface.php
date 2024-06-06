<?php

namespace app\services;

interface ImportServiceInterface
{
    /**
     * @return array
     */
    public function importRates(): array;
}
