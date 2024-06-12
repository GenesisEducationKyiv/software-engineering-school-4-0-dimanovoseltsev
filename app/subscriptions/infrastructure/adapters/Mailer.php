<?php

namespace app\subscriptions\infrastructure\adapters;

use app\currencies\domain\entities\Currency;
use app\subscriptions\application\adapters\MailerAdapterInterface;
use app\subscriptions\domain\entities\Subscription;
use yii\symfonymailer\Mailer as YiiMailer;

class Mailer implements MailerAdapterInterface
{
    /**
     * @param YiiMailer $mailer
     */
    public function __construct(
        private readonly YiiMailer $mailer,
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
            ->setTo($subscription->getEmail()->value())
            ->setSubject('Actual rate')
            ->setTextBody(
                sprintf("The current exchange rate as of %s is %f.", date("Y-m-d"), $currency->getRate()->value())
            )
            ->send();
    }
}
