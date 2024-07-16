<?php

namespace console\workers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\actions\RetrieveSubscriptionByEmailInterface;
use app\application\actions\SendEmailInterface;
use app\application\dto\MailSendDto;
use app\application\enums\CurrencyIso;
use app\application\exceptions\NotExistException;
use app\application\services\LogServiceInterface;
use Throwable;
use Yii;

class MailWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param SendEmailInterface $sendEmail
     * @param RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode
     * @param RetrieveSubscriptionByEmailInterface $retrieveSubscriptionByEmail
     * @param LogServiceInterface $logService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SendEmailInterface $sendEmail,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
        private readonly RetrieveSubscriptionByEmailInterface $retrieveSubscriptionByEmail,
        private readonly LogServiceInterface $logService,
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
        $email = $data['email'] ?? null;
        $currency = $data['currency'] ?? null;

        if (empty($email) || empty($currency)) {
            return true;
        }

        try {
            return $this->sendEmail->execute(
                new MailSendDto(
                    $this->retrieveCurrencyByCode->execute(CurrencyIso::from((string)$currency)),
                    $this->retrieveSubscriptionByEmail->execute((string)$email),
                )
            );
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
