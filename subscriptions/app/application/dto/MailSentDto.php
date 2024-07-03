<?php

namespace app\application\dto;

use app\application\traits\TimestampTrait;

readonly class MailSentDto
{
    use TimestampTrait;

    /**
     * @param string $email
     */
    public function __construct(private string $email)
    {
        $this->setCurrentTimestamp();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}
