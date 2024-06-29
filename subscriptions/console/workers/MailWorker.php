<?php

namespace console\workers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\exceptions\NotExistException;
use app\subscriptions\application\actions\SendEmailInterface;
use app\subscriptions\application\dto\SendEmailDto;
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
