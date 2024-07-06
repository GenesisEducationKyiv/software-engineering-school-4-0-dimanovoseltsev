<?php

namespace console\workers;

use app\application\actions\SendEmailInterface;
use app\application\exceptions\NotExistException;
use app\application\mappers\EventMapper;
use app\application\services\LogServiceInterface;
use Throwable;
use Yii;

class CurrencyWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param SendEmailInterface $sendEmail
     * @param LogServiceInterface $logService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SendEmailInterface $sendEmail,
        private readonly LogServiceInterface $logService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->maxAttempt = Yii::$app->params['currency']['mail']['maxAttempt'];
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
        // @todo debug
        echo '<pre>';
        print_r(array(
            'file' => __FILE__,
            'line' => __LINE__,
        ));
        echo '</pre>';
        die();
        $data = (array)json_decode($body, true);
        $eventId = $data['eventId'] ?? null;
        $eventType = $data['eventType'] ?? null;
        $timestamp = $data['timestamp'] ?? null;
        $eventData = $data['data'] ?? null;

        if (empty($eventId) || empty($eventType) || empty($timestamp) || empty($eventData)) {
            return true;
        }


        try {
            $convertor = new EventMapper();
            $this->logService->log(
                sprintf("Process event %s:%s [%d]", (string)$eventType, (string)$eventId, (int)$timestamp)
            );
            return $this->sendEmail->execute($convertor->convertMailCreateToDto($eventData));
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
