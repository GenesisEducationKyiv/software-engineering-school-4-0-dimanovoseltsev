<?php

namespace app\application\interfaces;

interface EventBusInterface
{
    /**
     * @param EventInterface $event
     * @return void
     */
    public function publish(EventInterface $event): void;
}
