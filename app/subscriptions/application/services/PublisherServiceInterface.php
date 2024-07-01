<?php

namespace app\subscriptions\application\services;

interface PublisherServiceInterface
{
    /**
     * @param string $email
     * @param string $currency
     * @return void
     */
    public function enqueueMessageForSending(string $email, string $currency): void;
}
