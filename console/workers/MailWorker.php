<?php

namespace console\workers;

use app\currencies\application\actions\RetrieveCurrencyByCodeInterface;
use app\models\Currency;
use app\models\Subscription;
use app\services\CurrenciesServiceInterface;
use app\services\MailServiceInterface;
use app\services\SubscriptionServiceInterface;
use app\shared\application\exceptions\NotExistException;
use app\subscriptions\application\actions\SendEmailInterface;
use Throwable;
use Yii;

class MailWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode
     * @param SendEmailInterface $sendEmail
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
        private readonly SendEmailInterface $sendEmail,
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


        try {
            $currency = $this->retrieveCurrencyByCode->execute((string)$data['currency']);
            return $this->sendEmail->execute($currency, (string)$data['email']);
        } catch (NotExistException $e) {
            return true;
        } catch (Throwable $e) {
            return $this->moveToFailQueue(
                Yii::$app->sendEmailFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
