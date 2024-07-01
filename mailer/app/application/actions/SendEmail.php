<?php

namespace app\application\actions;

use app\application\adapters\EventBusInterface;
use app\application\dto\MailSendDto;
use app\application\events\MailSentEvent;
use app\application\services\MailServiceInterface;

class SendEmail extends BaseAction implements SendEmailInterface
{
    /**
     * @param MailServiceInterface $mailService
     * @param EventBusInterface $eventBus
     */
    public function __construct(
        private readonly MailServiceInterface $mailService,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    /**
     * @param MailSendDto $dto
     * @return bool
     */
    public function execute(MailSendDto $dto): bool
    {
        $state = $this->mailService->sendMail($dto->getCurrency(), $dto->getSubscription());

        if ($state) {
            $this->eventBus->publish(new MailSentEvent($dto->getSubscription(), $dto->getTimestamp()));
        }

        return $state;
    }
}
