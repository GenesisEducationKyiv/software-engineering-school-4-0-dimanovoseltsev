<?php

namespace tests\components;


class DummyQueue extends \yii\queue\amqp_interop\Queue
{

    public function push($job)
    {
        return 'push';
    }

    protected function pushMessage($payload, $ttr, $delay, $priority)
    {
        return 'pushMessage';
    }
}
