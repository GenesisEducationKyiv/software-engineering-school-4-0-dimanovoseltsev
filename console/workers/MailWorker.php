<?php

namespace console\workers;

use app\models\Currency;
use app\models\Subscription;
use app\services\CurrenciesServiceInterface;
use app\services\MailServiceInterface;
use app\services\SubscriptionServiceInterface;
use Throwable;
use Yii;
use yii\helpers\Console;

class MailWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param MailServiceInterface $mailService
     * @param SubscriptionServiceInterface $subscriptionService
     * @param CurrenciesServiceInterface $currenciesService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly MailServiceInterface $mailService,
        private readonly SubscriptionServiceInterface $subscriptionService,
        private readonly CurrenciesServiceInterface $currenciesService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->maxAttempt = Yii::$app->params['workers']['mail']['maxAttempt'];
    }

    /**
     * @param $id
     * @param $body
     * @param $ttr
     * @param $attempt
     * @return bool
     */
    public function processMessage($id, $body, $ttr, $attempt): bool
    {
        $data = (array)json_decode($body, true);
        if (empty($data['email']) || empty($data['currency'])) {
            return true;
        }

        /** @var ?Currency $currency */
        $currency = $this->currenciesService->findByCode((string)$data['currency']);
        if ($currency === null) {
            return true;
        }

        /** @var  ?Subscription $subscription */
        $subscription = $this->subscriptionService->findByEmailAndNotSend((string)$data['email']);
        if ($subscription === null) {
            return true;
        }

        Console::output(sprintf("Send %s - %s", $currency->iso3, $subscription->email));

        try {
            $state = $this->mailService->sendActualRate($currency, $subscription);
            if ($state) {
                $this->subscriptionService->updateLastSend($subscription);
            }
            return $state;
        } catch (Throwable $e) {
            return $this->moveToFailQueue(
                Yii::$app->sendEmailFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
