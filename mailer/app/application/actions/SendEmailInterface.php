<?php

namespace app\application\actions;

use app\application\dto\MailSendDto;

interface SendEmailInterface
{
    /**
     * @param MailSendDto $dto
     * @return bool
     */
    public function execute(MailSendDto $dto): bool;
}
