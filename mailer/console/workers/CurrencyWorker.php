<?php

namespace console\workers;

use app\application\actions\SaveCurrencyInterface;
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
     * @param SaveCurrencyInterface $saveCurrency
     * @param LogServiceInterface $logService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SaveCurrencyInterface $saveCurrency,
        private readonly LogServiceInterface $logService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->maxAttempt = Yii::$app->params['workers']['currency']['maxAttempt'];
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

        $eventForm = $this->loadEvent($data);
        if ($eventForm === null) {
            return true;
        }

        $this->addEventLog($eventForm, $this->logService);

        try {
            $convertor = new EventMapper();
            $this->saveCurrency->execute($convertor->convertCurrencyUpdateToDto($eventForm->getData()));

            return true;
        } catch (NotExistException $e) {
            return true;
        } catch (Throwable $e) {
            return $this->moveToFailQueue(
                Yii::$app->currencyFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
