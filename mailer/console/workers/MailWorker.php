<?php

namespace console\workers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\actions\RetrieveSubscriptionByEmailInterface;
use app\application\actions\SendEmailInterface;
use app\application\dto\MailSendDto;
use app\application\enums\CurrencyIso;
use app\application\enums\State;
use app\application\exceptions\NotExistException;
use app\application\services\MetricServiceInterface;
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
     * @param MetricServiceInterface $metricService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SendEmailInterface $sendEmail,
        private readonly RetrieveCurrencyByCodeInterface $retrieveCurrencyByCode,
        private readonly RetrieveSubscriptionByEmailInterface $retrieveSubscriptionByEmail,
        private readonly MetricServiceInterface $metricService,
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
        $command = 'worker:mail';
        $start = (float)microtime(true);

        $data = (array)json_decode($body, true);
        $email = $data['email'] ?? null;
        $currency = $data['currency'] ?? null;

        if (empty($email) || empty($currency)) {
            return true;
        }

        try {
            $currencyIso = CurrencyIso::from((string)$currency);

            $state = $this->sendEmail->execute(
                new MailSendDto(
                    $this->retrieveCurrencyByCode->execute($currencyIso),
                    $this->retrieveSubscriptionByEmail->execute((string)$email),
                )
            );
            $this->metricService->trackCommands($command, State::SUCCESSFUL);
            $this->metricService->trackSendEmailTotal($currencyIso);

            return $state;
        } catch (NotExistException $e) {
            $this->metricService->trackCommands($command, State::SUCCESSFUL);
            return true;
        } catch (Throwable $e) {
            $this->metricService->trackCommands($command, State::FAILURE);
            return $this->moveToFailQueue(
                Yii::$app->sendEmailFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
