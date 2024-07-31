<?php

namespace app\application\actions;

use app\application\dto\MailSentDto;
use app\domain\entities\Subscription;

interface MailSentInterface
{
    /**
     * @param MailSentDto $dto
     * @return Subscription
     */
    public function execute(MailSentDto $dto): Subscription;
}
