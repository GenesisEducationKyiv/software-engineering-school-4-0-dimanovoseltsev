<?php

namespace app\application\mappers;

use app\application\dto\DeleteSubscriptionDto;
use app\application\dto\MailSendDto;
use app\application\dto\SaveCurrencyDto;
use app\application\dto\SaveSubscriptionDto;
use app\application\exceptions\NotValidException;
use app\application\forms\DeleteSubscriptionForm;
use app\application\forms\MailCreateEventForm;
use app\application\forms\SaveCurrencyForm;
use app\application\forms\SaveSubscriptionForm;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Id;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class EventMapper
{
    /**
     * @param array{
     *     email?: string,
     *     currency?: array{
     *         iso3?: string,
     *         rate?: float,
     *         updatedAt?: int,
     *       }
     *     } $eventData
     * @return MailSendDto
     * @throws NotValidException
     * @deprecated
     */
    public function convertMailCreateToDto(array $eventData): MailSendDto
    {
        $currencyInfo = $eventData['currency'] ?? null;
        $form = new MailCreateEventForm(
            $eventData['email'] ?? null,
            $currencyInfo['iso3'] ?? null,
            $currencyInfo['rate'] ?? null,
            $currencyInfo['updatedAt'] ?? null,
        );

        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $dto = new MailSendDto(
            new Currency(
                new Id(null),
                new Iso3($form->getCurrencyIso3()),
                new Rate($form->getCurrencyRate()),
                new Timestamp($form->getCurrencyUpdatedAt()),
            ),
            new Subscription(
                new Id(null),
                new Email($form->getEmail())
            )
        );
        $dto->setTimestamp($form->getCurrencyUpdatedAt());
        return $dto;
    }


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
