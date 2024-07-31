<?php

namespace app\application\actions;

use app\application\adapters\EventBusInterface;
use app\application\dto\CreateCurrencyDto;
use app\application\events\CurrencyUpdateEvent;
use app\application\exceptions\NotValidException;
use app\application\forms\CurrencyForm;
use app\application\services\CurrencyServiceInterface;
use app\domain\entities\Currency;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class CreateOrUpdateCurrency extends BaseAction implements CreateOrUpdateCurrencyInterface
{
    /**
     * @param CurrencyServiceInterface $service
     * @param EventBusInterface $eventBus
     */
    public function __construct(
        private readonly CurrencyServiceInterface $service,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    /**
     * @param Currency $currency
     * @return void
     */
    private function pushEventUpdate(Currency $currency): void
    {
        $this->eventBus->publish(new CurrencyUpdateEvent($currency));
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
            $entity = $this->service->create(
                new CreateCurrencyDto(
                    $form->getCode(),
                    $form->getRate(),
                    $form->getTimestamp(),
                )
            );
            $this->pushEventUpdate($entity);

            return $entity;
        }

        $entity->setRate(new Rate($form->getRate()));
        $entity->setUpdatedAt(new Timestamp($form->getTimestamp()));

        $entity = $this->service->save($entity);
        $this->pushEventUpdate($entity);

        return $entity;
    }
}
