<?php

namespace app\application\adapters;

use app\application\interfaces\EventInterface;

interface EventBusInterface
{
    /**
     * @param EventInterface $event
     * @return void
     */
    public function publish(EventInterface $event): void;
}
