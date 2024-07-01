<?php

namespace console\workers;

use app\application\actions\RetrieveActualCurrencyRateInterface;
use app\application\actions\SendEmailInterface;
use app\application\dto\MailSendDto;
use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;
use app\domain\entities\Subscription;
use app\domain\valueObjects\Email;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;
use Throwable;
use Yii;

class MailWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param SendEmailInterface $sendEmail
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
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

        $currencyInfo = $data['currency'];

        try {
            $currency = new Currency(
                new Iso3($currencyInfo['iso3'] ?? null),
                new Rate($currencyInfo['rate']?? null),
                new Timestamp($currencyInfo['updatedAt']?? null),
            );

            $subscription = new Subscription(new Email((string)$data['email']));
            return $this->sendEmail->execute(new MailSendDto($currency, $subscription, time()));
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
