<?php

use app\application\adapters\EventBusInterface;
use yii\db\Migration;

class m240706_093012_subscribe_to_subscription_create extends Migration
{
    public const  string EVENT = "subscription.create";

    /**
     * @param EventBusInterface $eventBus
     * @param array $config
     */
    public function __construct(
        private EventBusInterface $eventBus,
        array $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * @return void
     */
    public function up(): void
    {
        $this->eventBus->subscribe(
            (string)getenv("RABBITMQ_SUBSCRIPTION_QUEUE"),
            self::EVENT,
        );
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->eventBus->unsubscribe(
            (string)getenv("RABBITMQ_SUBSCRIPTION_QUEUE"),
            self::EVENT,
        );
    }
}
