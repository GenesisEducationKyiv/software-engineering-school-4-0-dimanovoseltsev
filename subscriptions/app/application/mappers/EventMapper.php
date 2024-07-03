<?php

namespace app\application\mappers;

use app\application\dto\MailSentDto;
use app\application\exceptions\NotValidException;
use app\application\forms\MailSentEventForm;

class EventMapper
{
    /**
     * @param array $eventData
     * @return MailSentDto
     * @throws NotValidException
     */
    public function convertMailSentToDto(array $eventData): MailSentDto
    {
        $form = new MailSentEventForm(
            $eventData['email'] ?? null
        );
        $form->setTimestamp((int)$eventData['timestamp'] ?? null);

        if(!$form->validate()){
           throw new NotValidException($form->getErrors());
        }

        $dto = new MailSentDto($form->getEmail());
        $dto->setTimestamp((int)$form->getTimestamp());
        return $dto;
    }
}
