<?php

namespace app\currencies\application\actions;

use app\currencies\domain\entities\Currency;
use app\shared\application\exceptions\NotValidException;

interface ImportRatesInterface
{
    /**
     * @return Currency[]
     * @throws NotValidException
     */
    public function execute(): array;
}
