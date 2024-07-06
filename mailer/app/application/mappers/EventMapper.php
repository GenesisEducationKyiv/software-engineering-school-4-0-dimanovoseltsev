<?php

namespace app\application\mappers;

use app\application\dto\DeleteSubscriptionDto;
use app\application\dto\SaveCurrencyDto;
use app\application\dto\SaveSubscriptionDto;
use app\application\exceptions\NotValidException;
use app\application\forms\DeleteSubscriptionForm;
use app\application\forms\SaveCurrencyForm;
use app\application\forms\SaveSubscriptionForm;

class EventMapper
{
    /**
     * @param array{
     *    iso3?: string,
     *    rate?: float,
     *    updatedAt?: int,
     * } $eventData
     * @return SaveCurrencyDto
     * @throws NotValidException
     */
    public function convertCurrencyUpdateToDto(array $eventData): SaveCurrencyDto
    {
        $form = new SaveCurrencyForm(
            $eventData['iso3'] ?? null,
            $eventData['rate'] ?? null,
            $eventData['updatedAt'] ?? null,
        );

        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        return new SaveCurrencyDto($form->getIso3(), $form->getRate(), $form->getUpdatedAt());
    }

    /**
     * @param array{email?: string} $eventData
     * @return SaveSubscriptionDto
     * @throws NotValidException
     */
    public function convertSubscriptionCreateToDto(array $eventData): SaveSubscriptionDto
    {
        $form = new SaveSubscriptionForm($eventData['email'] ?? null);

        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        return new SaveSubscriptionDto($form->getEmail());
    }

    /**
     * @param array{email?: string} $eventData
     * @return DeleteSubscriptionDto
     * @throws NotValidException
     */
    public function convertSubscriptionDeleteToDto(array $eventData): DeleteSubscriptionDto
    {
        $form = new DeleteSubscriptionForm($eventData['email'] ?? null);

        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        return new DeleteSubscriptionDto($form->getEmail());
    }
}
