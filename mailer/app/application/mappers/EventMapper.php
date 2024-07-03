<?php

namespace app\application\mappers;

use app\application\dto\MailSendDto;
use app\application\exceptions\NotValidException;
use app\application\forms\MailCreateEventForm;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;

class EventMapper
{
    /**
     * @param array $eventData
     * @return MailSendDto
     * @throws NotValidException
     */
    public function convertMailCreateToDto(array $eventData): MailSendDto
    {
        $currencyInfo = $eventData['currency'] ?? null;
        $form = new MailCreateEventForm(
            $currencyInfo['email'] ?? null,
            $currencyInfo['iso3'] ?? null,
            $currencyInfo['rate'] ?? null,
            $currencyInfo['updatedAt'] ?? null,
        );

        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $dto = new MailSendDto(
            new Currency(
                new Iso3($form->getCurrencyIso3()),
                new Rate($form->getCurrencyRate()),
                new Timestamp($form->getCurrencyUpdatedAt()),
            ),
            new Subscription(
                new Email($form->getEmail())
            )
        );
        $dto->setTimestamp($form->getCurrencyUpdatedAt());
        return $dto;
    }
}
