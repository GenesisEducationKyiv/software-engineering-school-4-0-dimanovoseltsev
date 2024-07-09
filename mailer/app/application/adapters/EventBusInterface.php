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

    /**
     * @param string $queue
     * @param string $routing
     * @return void
     */
    public function subscribe(string $queue, string $routing): void;

    /**
     * @param string $queue
     * @param string $routing
     * @return void
     */
    public function unsubscribe(string $queue, string $routing): void;
}
