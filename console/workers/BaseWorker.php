<?php

namespace console\workers;

use app\shared\application\adapters\MessageBrokerInterface;
use Exception;
use Throwable;
use yii\queue\amqp_interop\Command;
use yii\queue\amqp_interop\Queue;

abstract class BaseWorker extends Command
{
    protected int $maxAttempt = 5;

    /**
     * This method sets the message handler for the queue object
     * and starts listening for incoming messages.
     *
     * @return void
     */
    public function actionListen(): void
    {
        $this->queue->messageHandler = [$this, 'processMessage'];
        $this->queue->listen();
    }

    /**
     * This method checks if the attempt count is below the maximum attempt count.
     * If it is, it returns false. Otherwise, it pushes the given data to the fail queue.
     *
     * @param Queue $queue The queue object to push data to.
     * @param mixed $data The data to be pushed to the fail queue.
     * @param int $attempt The current attempt count.
     * @return bool Returns true if the data was pushed to the fail queue successfully, false otherwise.
     */
    protected function moveToFailQueue(Queue $queue, array $data, int $attempt): bool
    {
        if ($attempt < $this->maxAttempt) {
            return false;
        }
        $queue->push($data);
        return true;
    }

    /**
     * @param array $data
     * @param Throwable|Exception $e
     * @return array
     */
    protected function appendError(array $data, Throwable|Exception $e): array
    {
        $data['errorMessage'] = $e->getMessage();
        $data['errorClass'] = get_class($e);
        return $data;
    }
}
