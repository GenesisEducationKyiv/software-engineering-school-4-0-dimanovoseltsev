<?php

namespace app\currencies\application\actions;

use app\currencies\application\dto\CreateCurrencyDto;
use app\currencies\application\forms\CurrencyForm;
use app\currencies\application\services\CurrencyServiceInterface;
use app\currencies\domain\entities\Currency;
use app\currencies\domain\valueObjects\Rate;
use app\shared\application\exceptions\NotValidException;
use app\shared\domain\valueObjects\Timestamp;

class CreateOrUpdateCurrency extends BaseAction implements CreateOrUpdateCurrencyInterface
{
    /**
     * @param CurrencyServiceInterface $service
     */
    public function __construct(
        private readonly CurrencyServiceInterface $service,
    ) {
    }

    /**
     * @param CurrencyForm $form
     * @return Currency
     * @throws NotValidException
     */
    public function execute(CurrencyForm $form): Currency
    {
        if (
            !$form->validate()
            || $form->getCode() === null
            || $form->getRate() === null
        ) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByCode($form->getCode());
        if ($entity === null) {
            return $this->service->create(
                new CreateCurrencyDto(
                    $form->getCode(),
                    $form->getRate(),
                    $form->getTimestamp(),
                )
            );
        }

        $entity->setRate(new Rate($form->getRate()));
        $entity->setUpdatedAt(new Timestamp($form->getTimestamp()));
        return $this->service->save($entity);
    }
}
