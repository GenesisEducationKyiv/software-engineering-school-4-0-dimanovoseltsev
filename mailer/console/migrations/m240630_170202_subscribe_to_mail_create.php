<?php

use app\application\adapters\EventBusInterface;
use yii\db\Migration;

class m240630_170202_subscribe_to_mail_create extends Migration
{
    private const string EVENT = "mail.create";

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
            (string)getenv("RABBITMQ_SEND_EMAIL_QUEUE"),
            self::EVENT,
        );
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->eventBus->unsubscribe(
            (string)getenv("RABBITMQ_SEND_EMAIL_QUEUE"),
            self::EVENT,
        );
    }
}
