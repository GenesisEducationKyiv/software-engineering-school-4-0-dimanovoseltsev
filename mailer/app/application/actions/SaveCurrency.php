<?php

namespace app\application\actions;

use app\application\dto\SaveCurrencyDto;
use app\application\services\CurrencyServiceInterface;
use app\application\services\LogServiceInterface;
use app\domain\entities\Currency;
use app\domain\valueObjects\Id;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class SaveCurrency extends BaseAction implements SaveCurrencyInterface
{
    /**
     * @param CurrencyServiceInterface $currencyService
     * @param LogServiceInterface $logService
     */
    public function __construct(
        private readonly CurrencyServiceInterface $currencyService,
        private readonly LogServiceInterface $logService,

    ) {
    }

    /**
     * @param SaveCurrencyDto $dto
     * @return Currency
     */
    public function execute(SaveCurrencyDto $dto): Currency
    {
        $entity = $this->currencyService->getByCode($dto->getIso3());
        if ($entity === null) {
            $entity = new Currency(
                new Id(null),
                new Iso3($dto->getIso3()),
                new Rate(1.0),
                new Timestamp(1),
            );
        }

        $entity->setRate(new Rate($dto->getRate()));
        $entity->setUpdatedAt(new Timestamp($dto->getUpdatedAt()));

        $this->logService->debug(
            sprintf(
                "Save currency %s %f",
                $entity->getIso3()->value(),
                $entity->getRate()->value(),
            )
        );

        return $this->currencyService->save($entity);
    }
}
