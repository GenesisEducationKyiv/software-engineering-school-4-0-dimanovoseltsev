<?php

namespace app\services;


use app\models\Currency;
use app\models\Subscription;
use yii\symfonymailer\Mailer;

class MailService implements MailServiceInterface
{
    /**
     * @param Mailer $mailer
     */
    public function __construct(
        private readonly Mailer $mailer,
    ) {
    }

    /**
     * @param Currency $currency
     * @param Subscription $subscription
     * @return bool
     */
    public function sendActualRate(Currency $currency, Subscription $subscription): bool
    {
        return $this->mailer
            ->compose()
            ->setTo($subscription->email)
            ->setSubject('Actual rate')
            ->setTextBody(sprintf("The current exchange rate as of %s is %f.", date("Y-m-d"), $currency->rate))
            ->send();
    }
}
