<?php

namespace console\workers;

use app\application\actions\MailSentInterface;
use app\application\dto\MailSentDto;
use app\application\exceptions\NotExistException;
use Throwable;
use Yii;

class MailSentWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param MailSentInterface $mailSent
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly MailSentInterface $mailSent,
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

        if (empty($data['email']) || empty($data['timestamp'])) {
            return true;
        }

        try {
            $this->mailSent->execute(
                new MailSentDto(
                    (string)$data['email'],
                    (int)$data['timestamp'],
                )
            );

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
