<?php

namespace console\workers;

use app\application\actions\DeleteSubscriptionInterface;
use app\application\actions\SaveSubscription;
use app\application\actions\SendEmailInterface;
use app\application\exceptions\NotExistException;
use app\application\mappers\EventMapper;
use app\application\services\LogServiceInterface;
use Throwable;
use Yii;

class SubscriptionWorker extends BaseWorker
{
    /**
     * @param $id
     * @param $module
     * @param SaveSubscription $saveSubscription
     * @param DeleteSubscriptionInterface $deleteSubscription
     * @param LogServiceInterface $logService
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        private readonly SaveSubscription $saveSubscription,
        private readonly DeleteSubscriptionInterface $deleteSubscription,
        private readonly LogServiceInterface $logService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->maxAttempt = Yii::$app->params['subscription']['mail']['maxAttempt'];
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

            // @todo fabric
            match ($eventForm->getEventType()) {
                'subscription.create' => $this->saveSubscription->execute(
                    $convertor->convertSubscriptionCreateToDto($eventForm->getData())
                ),
                'subscription.delete' => $this->deleteSubscription->execute(
                    $convertor->convertSubscriptionDeleteToDto($eventForm->getData())
                ),
                default => null
            };

            return true;
        } catch (NotExistException $e) {
            return true;
        } catch (Throwable $e) {
            return $this->moveToFailQueue(
                Yii::$app->subscriptionFailQueue,
                $this->appendError((array)$data, $e),
                (int)$attempt
            );
        }
    }
}
