<?php

namespace app\application\actions;

use app\application\exceptions\NotValidException;
use app\domain\entities\Currency;

interface ImportRatesInterface
{
    /**
     * @return Currency[]
     * @throws NotValidException
     */
    public function execute(): array;
}
