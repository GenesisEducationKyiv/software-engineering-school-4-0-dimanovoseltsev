<?php

namespace console\workers;

use app\application\actions\RetrieveActualCurrencyRateInterface;
use app\application\actions\SendEmailInterface;
use app\application\dto\SendEmailDto;
use app\application\exceptions\NotExistException;
use app\domain\entities\Currency;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use Throwable;
use Yii;

class MailWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param RetrieveActualCurrencyRateInterface $retrieveActualCurrencyRate
     * @param SendEmailInterface $sendEmail
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly RetrieveActualCurrencyRateInterface $retrieveActualCurrencyRate,
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
                new Iso3($currencyInfo['iso3']),
                new Rate($currencyInfo['rate']),
                new Rate($currencyInfo['updatedAt']),
            );
            return $this->sendEmail->execute($currency, new SendEmailDto((string)$data['email'], time()));
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
