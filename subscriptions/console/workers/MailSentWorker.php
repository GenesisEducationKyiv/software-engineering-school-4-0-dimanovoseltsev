<?php

namespace console\workers;

use app\application\actions\MailSentInterface;
use app\application\exceptions\NotExistException;
use app\application\mappers\EventMapper;
use app\application\services\LogServiceInterface;
use Throwable;
use Yii;

class MailSentWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param MailSentInterface $mailSent
     * @param LogServiceInterface $logService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly MailSentInterface $mailSent,
        private readonly LogServiceInterface $logService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->maxAttempt = Yii::$app->params['workers']['mail-sent']['maxAttempt'];
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

        $eventId = $data['eventId'] ?? null;
        $eventType = $data['eventType'] ?? null;
        $timestamp = $data['timestamp'] ?? null;
        $eventData = $data['data'] ?? null;

        if (empty($eventId) || empty($eventType) || empty($timestamp) || empty($eventData)) {
            return true;
        }

        try {
            $this->logService->error(
                sprintf("Process event %s:%s [%d]", (string)$eventType, (string)$eventId, (int)$timestamp)
            );

            $convertor = new EventMapper();
            $this->mailSent->execute($convertor->convertMailSentToDto((array)$eventData));
            return true;
        } catch (NotExistException $e) {
            return true;
        } catch (Throwable $e) {
            return $this->moveToFailQueue(
                Yii::$app->mailSentFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
