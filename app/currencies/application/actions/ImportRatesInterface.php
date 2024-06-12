<?php

namespace app\currencies\application\actions;

use app\currencies\application\dto\CreateCurrencyDto;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\services\CurrencyServiceInterface;
use app\currencies\application\services\ImportCurrencyServiceInterface;
use app\currencies\domain\entities\Currency;
use app\currencies\domain\valueObjects\Rate;
use app\shared\application\exceptions\NotValidException;
use app\shared\domain\valueObjects\Timestamp;

interface ImportRatesInterface
{
    /**
     * @return Currency[]
     * @throws NotValidException
     */
    public function execute(): array;
}
